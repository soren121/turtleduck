# Installing Turtleduck

This software has a lot of interconnected parts, so bear with me; this will 
take a bit more time to put together than the average Spigot plugin.

### Step 1: Register with Telegram
Turtleduck makes use of the [Telegram Bot API](https://core.telegram.org/bots).
Use of the API is completely free, though you will have to register an account 
for your new bot.

 1. Obtain a Telegram Bot API token from the @BotFather account on Telegram. 
 [Instructions can be found here.](https://core.telegram.org/bots#6-botfather)
 2. Save the API token that the bot gives you. You'll need it later.
 
### Step 2: Generate the HMAC signing key
Turtleduck uses a 16-byte hexadecimal key to sign its internal requests. This 
ensures that outsiders cannot abuse Turtleduck to spam your Minecraft server 
chat or your registered Telegram chats.
 
 1. A Python script named `generate-hmac.py` is included in the base directory 
 of this repo that will generate the key for you. Run it in your terminal to 
 generate a key. (You will need Python 2 or 3 installed.)
 2. Save the generated HMAC key for later.
 
### Step 3: Install the web component
 1. Extract the contents of the web-component directory to a 
 publicly-accessible directory on your web server.
 2. Install [Composer](https://getcomposer.org/) if you haven't already.
 3. Open a shell where you extracted the web-component directory.
 4. Run `composer install` to download the requisite dependencies.

### Step 4: Database setup
 1. Create a new database on your MySQL server, if you wish.
 2. Import the `install.sql` file from the web-component directory into your 
 MySQL database.
 3. To add allowed users, simply add new rows to the `turtleduck_allowed_users`
 table. The username column is the Telegram username you wish to allow, and 
 you don't need to set anything in the *date_added* column (it will be 
 updated automatically.)
     - You can execute this SQL query to add allowed users:  
    `INSERT INTO turtleduck_allowed_users VALUES('MyUsername', NULL);`
     
     Just remember to replace *MyUsername* with the username you wish to allow.

### Step 5: Spigot configuration
 1. Open your Spigot server's `server.properties` file.
 2. Enable RCON.
     - Set `enable-rcon=true`. Add this to your config if it doesn't exist.
     - Set `rcon-password` to a long, random alphanumeric password. I recommend 
     using a strong password generator for this.
     - Ensure that the `rcon.port` setting exists. Normally, this should be set 
     to 25575.
 3. Save your `server.properties` config.
 4. Install the Turtleduck plugin, and restart your Spigot server.
 5. A Turtleduck folder should have been generated in your server's plugins
 directory. Open the `config.yml` file that's inside it.
 6. Set the `hmacKey` variable to the HMAC signing key that you generated in
 Step 2.
 7. Set the `postUrl` variable to the URL of 
 `turtleduck/spigot-interface.php` on your web server. For example, if your 
 domain is *example.com* and you extracted the web-component directory to 
 the root of your web server's public directory, this would be:
 `https://www.example.com/turtleduck/spigot-interface.php`
 8. Save the `config.yml` file.
 9. Restart your Spigot server again.
 
### Step 6: Web component configuration
 1. Open the `config.php` file on your web server and set the following 
 constants accordingly.
     - *MC_SERVER_ADDR*: The address of your Spigot server.
     If your Spigot server is on the same machine as your web server, then 
     `localhost` should suffice.
     - *QUERY_PORT*: The port that your Spigot server runs on. Usually, this 
     is 25565 unless you changed it in Spigot's `server.properties`.
     - *RCON_PORT*: The port that your Spigot server's RCON (remote console) 
     service runs on. Usually, this is 25575 unless you changed it in Spigot's 
     `server.properties`.
     - *RCON_PASSWORD*: The RCON password that you set in Step 5.
     - *DB_HOSTNAME*: The hostname of your MySQL server. If it's on the same
     machine as your web server, then `localhost` should suffice.
     - *DB_NAME*: The name of the database you setup in Step 4.
     - *DB_PORT*: The port that your MySQL server runs on. Usually 3306.
     - *DB_USER*: The name of the MySQL user that this application will use.
     - *DB_PASS*: The password of the MySQL user that this application will use.
     - *TURTLEDUCK_HMAC*: The HMAC signing key that you generated in Step 2.
     - *TURTLEDUCK_TELEGRAM_TOKEN*: The API token that you received in Step 1.
 2. Save the `config.php` file.
 
### Step 7: Configure the Webhook
**TODO**

### Step 8: Register a Telegram chat to receive messages
 1. Start a conversation with your bot on Telegram. You can either initiate a 
 regular chat with it, or add the bot and yourself to a new group.
 2. Send the `/register` command. If you are an allowed user, it should respond 
 with "Registered successfully!"
 
Now that you've registered this chat, all messages sent with /sos on your 
Minecraft server will be forwarded to this chat. You can register up to 
**five** Telegram chats to receive messages. To reply to a message, use the 
"Reply To" action in Telegram.