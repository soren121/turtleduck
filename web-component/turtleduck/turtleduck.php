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
use xPaw\SourceQuery\SourceQuery;
use GuzzleHttp\Client;

class Turtleduck {
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    private function handleTelegramResponse($response) {
        if($response->getStatusCode() >= 500) {
            return false;
        }
        else if($response->getStatusCode() >= 300) {
            error_log("Turtleduck: " . $response->getReasonPhrase());
            if($response->getStatusCode() == 401) {
                throw new Exception('Invalid access token provided');
            }
            
            return false;
        }
        else {
            return json_decode($response->getBody(), true);
        }
    }

    public function telegramRequestGet($method, $parameters) {
        if(!is_string($method)) {
            error_log("Method name must be a string\n");
            return false;
        }
        
        if(!$parameters) {
            $parameters = array();
        }
        elseif(!is_array($parameters)) {
            error_log("Parameters must be an array\n");
            return false;
        }
        
        foreach($parameters as $key => &$val) {
            // encoding to JSON array parameters, for example reply_markup
            if (!is_numeric($val) && !is_string($val)) {
                $val = json_encode($val);
            }
        }
        
        $client = new Client([
            'base_uri' => TURTLEDUCK_TELEGRAM_API_URL,
            'timeout' => 10
        ]);
        
        $response = $client->request('GET', $method, [
            'query' => $parameters
        ]);
        
        return $this->handleTelegramResponse($response);
    }

    public function telegramRequestPost($method, $parameters){
        if (!is_string($method)) {
            error_log("Method name must be a string\n");
            return false;
        }
        if (!$parameters) {
            $parameters = array();
        }
        else if (!is_array($parameters)) {
            error_log("Parameters must be an array\n");
            return false;
        }
        
        $parameters['method'] = $method;
        
        $client = new Client([ 'timeout' => 10 ]);
        $response = $client->post(TURTLEDUCK_TELEGRAM_API_URL, [
            'json' => $parameters
        ]);
        
        return $this->handleTelegramResponse($response);
    }

    public function registerChat($chatID, $username) {
        if($this->pdo) {
            // Check that the user is allowed to register chats
            // and that we haven't exceeded the maximum number of
            // registered chats
            $chatCheck = $this->pdo->prepare('
                SELECT username
                FROM turtleduck_allowed_users
                WHERE 
                    username = ? AND
                    (SELECT COUNT(*) FROM turtleduck_chats) < 5'
            );
            $chatCheck->execute([$username]);
            
            if($row = $chatCheck->fetch()) {
                $insert = $this->pdo->prepare('
                    INSERT INTO turtleduck_chats 
                    (chat_id, registration_time) 
                    VALUES(?, NOW())'
                );
                
                return $insert->execute([$chatID]);
            }
        }
        
        return false;
    }

    public function deregisterChat($chatID) {
        if($this->pdo) {
            $delete = $this->pdo->prepare('
                DELETE FROM turtleduck_chats 
                WHERE chat_id = ?'
            );
                
            return $delete->execute([$chatID]);
        }
        else {
            return false;
        }   
    }
    
    public function broadcastMessage($message) {
        if($this->pdo) {
            $chats = $this->pdo->query('SELECT * FROM turtleduck_chats LIMIT 5');
            if($chats !== false) {
                foreach($chats as $row) {
                    $this->telegramRequestPost("sendMessage", array(
                        'chat_id' => $row['chat_id'],
                        'text' => $message,
                        'parse_mode' => 'Markdown'
                    ));
                }
                
                return true;
            }
            else {
                error_log("unable to fetch chats " . $chats->errorInfo()[2]);
            }
        }
        else {
            error_log("Unable to connect to database");
        }
        
        return false;
    }
    
    public function forwardToSpigot($username, $message) {
        $query = new SourceQuery();
        $command = "tdsay $username $message";

        try {
            $query->Connect(MC_SERVER_ADDR, RCON_PORT, RCON_TIMEOUT, SourceQuery::SOURCE);
            $query->SetRconPassword(RCON_PASSWORD);
            
            $query->Rcon($command);
        }
        catch(Exception $e) {
            error_log($e->getMessage());
        }
        finally {
            $query->Disconnect();
        }
    }
    
}
