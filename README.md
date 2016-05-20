**PLEASE NOTE**: Turtleduck is considerably more complex than the average 
Spigot plugin. The installation process is very involved and is only 
recommended for experienced server administrators.

# Turtleduck

Turtleduck is a [Telegram bot](https://core.telegram.org/bots) that is 
designed to give players on a [Spigot MC server](https://www.spigotmc.org/) 
an instant channel of communication with the server operators. It works by 
providing a command (`/sos`) to players which forwards the given message to 
a Telegram chat or group. Operators can also reply directly to messages 
received via Telegram, which will be relayed back to the Spigot server chat.

Turtleduck is comprised of two parts: a PHP component that runs on a 
web server which acts as a middle-man between the Telegram API and your Spigot 
server, and a Java plugin for Spigot that connects to the web server.

Runtime requirements:
 * A web server running PHP 5.6+ and MySQL 5.5+/MariaDB 10.0+.
 * Java JRE 8u72+.
 * Spigot 1.9+ with RCON enabled.

Compile-time requirements (for the Spigot plugin):
 * Java JDK 8u72+.
 * Maven 3.2+.
 
## Installation

See INSTALL.md for instructions.

## License

Licensed under the [GNU GPL v3](https://www.gnu.org/licenses/gpl.html) (or, at
your option, any later version.) See LICENSE.txt for the full license text.
