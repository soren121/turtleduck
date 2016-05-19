# turtleduck

Turtleduck is a [Telegram bot](https://core.telegram.org/bots) that is designed to allow 
players on a Minecraft server to contact the admins. Strictly speaking, it can work 
independently of a Minecraft server, but some functionality only works with one.

Turtleduck is comprised of two parts: a PHP component that runs on a webserver which handles 
communication with the Telegram server and logging, and a Java plugin for the Spigot MC 
server that allows messages to be sent to a Telegram group via an in-game command.

Runtime requirements:
 * A web server with PHP 5.6+ and a recent version of MySQL/MariaDB.
 * Java JRE 8u40+.
 * Spigot 1.8+ with RCON enabled. (Tested with 1.8.8 only.)

Compile requirements:
 * Java JDK 8u40+.
 * Maven 3.2+.
 
## Installation

See INSTALL.md for instructions.

## License

Licensed under the GNU GPL v3 (or, at your option, any later version.) See LICENSE.txt for more info.