<?php
/*
    Turtleduck, a Telegram notification bot designed for Minecraft servers
    Copyright (C) 2016 Nicholas Narsing <soren121@sorenstudios.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/turtleduck.php';

function buildBodyString($params) {
    $msg = "";
    foreach($params as $k => $v) {
        $msg .= sprintf("%s=%s&", $k, $v);
    }
    
    $msg = substr($msg, 0, -1);
    return $msg;
}

function generateHmacSignature($body, $salt) {
    $msg = $body . (string)$salt;
    
    $signature = hash_hmac('sha1', $msg, TURTLEDUCK_HMAC);
    return $signature;
}

if(isset($_GET['signature'], $_GET['salt'], $_POST['message']) && (time() - 65) <= $_GET['salt']) {
    $match = false;
    $generatedSignature = generateHmacSignature(buildBodyString($_POST), $_GET['salt']);
    
    if(hash_equals($generatedSignature, $_GET['signature'])) {
        $match = true;
    }
    
    if($match) {
        if($pdo = Database::getConnection()) {
            $insertQuery = $pdo->prepare('
                INSERT INTO turtleduck_spigot_log 
                (hash_match, remote_hmac, message, salt, time) 
                VALUES(?, ?, ?, ?, NOW())'
            );
            
            $insertQuery->execute([
                (int)$match,
                $_GET['signature'], 
                $_POST['message'], 
                $_GET['salt']
            ]);
        }
        
        $td = new Turtleduck();
        $message = strip_tags($_POST['message']);
        $username = "";
        
        if(isset($_POST['username'])) {
            $username = strip_tags($_POST['username']);
        }
        
        $td->broadcastMessage("*[MC:{$username}]* $message");
    }
    else {
        header('HTTP/1.1 500 Internal Server Error');
    }
}
else {
    header('HTTP/1.1 500 Internal Server Error');
}
