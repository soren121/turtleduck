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

function webhookRequest($method, $parameters) {
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
    
    $parameters["method"] = $method;
    header("Content-Type: application/json");
    echo json_encode($parameters);
    
    return true;
}

function processMessage($td, $message){
    // Debug
    //error_log(json_encode($message));
    
    // process incoming message
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];
    $username = $message['from']['username'];
    
    // incoming text message
    if (isset($message['text'])) {
        $text = $message['text'];
        
        // Introduce yourself!
        if (strpos($text, "/start") === 0) {
            webhookRequest("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => 'Turtleduck bot, reporting for duty!'
            ));
        }
        // Deregister chat
        // Will not inform user if they were not registered to begin with
        else if(strpos($text, "/stop") === 0 || strpos($text, "/deregister") === 0) {
            if($td->deregisterChat($chat_id)) {
                $deregisterText = "Deregistered successfully! Bye!";
            }
            else {
                $deregisterText = "Unable to deregister this chat.";
            }
            
            webhookRequest("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => $deregisterText
            ));
        }
        // Register chat to receive messages
        else if(strpos($text, "/register") === 0) {
            if($td->registerChat($chat_id, $username)) {
                $registerText = "Registered successfully!";
            }
            else {
                $registerText = "Unable to register this chat.";
            }
            
            webhookRequest("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => $registerText
            ));
        }
        else if(strpos($text, "/broadcast") === 0) {
            if($td->isUserAllowed($username) && strlen($text) >= 12) {
                $td->forwardToSpigot($username, substr($text, 11));
            }
            else {
                webhookRequest("sendMessage", array(
                    'chat_id' => $chat_id,
                    "text" => "Unable to broadcast message."
                ));
            }
        }
        else if(isset($message['reply_to_message'])) {
            if(strpos($message['reply_to_message']['text'], '[MC:') === 0) {
                $td->forwardToSpigot($username, $text);
            }
        }
        else if ($text === "Hello" || $text === "Hi") {
            webhookRequest("sendMessage", array(
                'chat_id' => $chat_id,
                "text" => 'Nice to meet you.'
            ));
        }
    }
}

// if (php_sapi_name() == 'cli') {
//     // if run from console, set or delete webhook
//     apiRequest('setWebhook', array(
//         'url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL
//     ));
//     exit;
// }

if(isset($_GET['token']) && hash_equals($_GET['token'], TURTLEDUCK_TELEGRAM_TOKEN)) {
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);

    if($update) {
        $td = new Turtleduck();
        if(isset($update["message"])) {
            processMessage($td, $update["message"]);
        }
    }
}
else {
    header('HTTP/1.1 403 Forbidden');
}
