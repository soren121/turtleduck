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

class Database {

	private static $instance;
	private $connection;

	// The constructor can only be called by getConnection
	// It returns either a PDO connection object, or false if it failed
	private function __construct() {
        try {
            $this->connection = new PDO(DB_CONNECT_STR, DB_USER, DB_PASS);
        }
		catch(PDOException $e) {
			$this->connection = false;
		}
	}

	public function __destruct() {
		$this->connection = null;
	}
	
	// Singletons should not be cloned
	// Throw a fatal error if cloning is attempted
	private function __clone() {}

	// If a connection has not already been established, call the constructor
	// Otherwise, return our connection
	public static function getConnection() {
		if(self::$instance === null) {
			self::$instance = new Database();
		}
		
		return self::$instance->connection;
	}
	
}
