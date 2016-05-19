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

define('BASE_DIR', __DIR__);

define('MC_SERVER_ADDR', 'localhost');
define('QUERY_PORT', 25565);
define('RCON_PORT', 25575);
define('RCON_PASSWORD', '');
define('RCON_TIMEOUT', 1);

define('DB_HOSTNAME', 'localhost');
define('DB_NAME', '');
define('DB_PORT', 3306);
define('DB_USER', '');
define('DB_PASS', '');

define('TURTLEDUCK_HMAC', '');
define('TURTLEDUCK_TELEGRAM_TOKEN', '');

// Do not edit below this line ------------------------------------------------

define('DB_CONNECT_STR', 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=utf8');
define('TURTLEDUCK_TELEGRAM_API_URL', 'https://api.telegram.org/bot'. TURTLEDUCK_TELEGRAM_TOKEN . '/');

session_start();
require_once BASE_DIR . '/vendor/autoload.php';
require_once BASE_DIR . '/includes/db.php';
