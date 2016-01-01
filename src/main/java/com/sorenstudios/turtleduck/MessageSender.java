/*
    Turtleduck, a Telegram notification bot designed for Minecraft servers
    Copyright (C) 2016 Nicholas Narsing <soren121@sorenstudios.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published 
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

package com.sorenstudios.turtleduck;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URI;
import java.net.URISyntaxException;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.time.Instant;
import java.util.ArrayList;
import java.util.Formatter;
import java.util.List;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.StatusLine;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.utils.URIBuilder;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.message.BasicNameValuePair;

public class MessageSender {
    
    private String postUrl;
    private String hmacKey;
    
    public MessageSender(String postUrl, String hmacKey) {
        this.postUrl = postUrl;
        this.hmacKey = hmacKey;
    }
    
    private String toHexString(byte[] bytes) {
        Formatter formatter = new Formatter();
		
		for (byte b : bytes) {
			formatter.format("%02x", b);
		}

		return formatter.toString();
    }
    
    private URI generateUri(String body) {
        long salt = Instant.now().getEpochSecond();
        String msg = body + String.valueOf(salt);
        String signature;
        
        try {
            SecretKeySpec signingKey = new SecretKeySpec(this.hmacKey.getBytes(), "HmacSHA1");
            Mac mac = Mac.getInstance("HmacSHA1");
            mac.init(signingKey);
            signature = toHexString(mac.doFinal(msg.getBytes()));
        }
        catch(InvalidKeyException | NoSuchAlgorithmException ex) {
            throw new IllegalArgumentException(ex);
        }
        
        try {
            return new URIBuilder(this.postUrl)
            .setParameter("signature", signature)
            .setParameter("salt", String.valueOf(salt))
            .build();
        }
        catch(URISyntaxException ex) {
            throw new IllegalArgumentException(ex);
        }
    }
    
    public boolean send(String message) {
        HttpClient httpClient = HttpClients.createDefault();
        HttpPost httpPost = new HttpPost(generateUri("message=" + message));

        List<NameValuePair> params = new ArrayList<NameValuePair>(1);
        params.add(new BasicNameValuePair("message", message));
        try {
            httpPost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
        }
        catch (UnsupportedEncodingException ex) {
            ex.printStackTrace();
            return false;
        }

        try {
            HttpResponse response = httpClient.execute(httpPost);
            StatusLine statusLine = response.getStatusLine();
            if (statusLine.getStatusCode() >= 300) {
                return false;
            }
        }
        catch(IOException ex) {
            ex.printStackTrace();
            return false;
        }
        
        return true;
    }
    
}