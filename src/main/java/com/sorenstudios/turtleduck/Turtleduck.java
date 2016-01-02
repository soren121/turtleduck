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

import org.bukkit.ChatColor;
import org.bukkit.command.CommandExecutor;
import org.bukkit.command.ConsoleCommandSender;
import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.configuration.file.FileConfiguration;

public class Turtleduck extends JavaPlugin {
    
    @Override
    public void onEnable() {
    	// Save config if it isn't there already
    	this.saveDefaultConfig();
        
        registerCommands();
		
        getLogger().info("Loaded successfully!");
    }
    
    private void registerCommands() {
        CommandExecutor sendMessage = (sender, cmd, label, args) -> {
            if(args.length >= 1) {
                String message = String.join(" ", args);
                
                FileConfiguration config = this.getConfig();
                String postUrl = config.getString("postUrl");
                String hmacKey = config.getString("hmacKey");
                MessageSender ms = new MessageSender(postUrl, hmacKey, getLogger());
                
                if(!ms.send(message)) {
                    sender.sendMessage(ChatColor.RED + "Message failed to send.");
                }
                return true;
            }
            return false;
        };
        
        CommandExecutor broadcast = (sender, cmd, label, args) -> {
            if(sender instanceof ConsoleCommandSender) {
                if(args.length >= 2) {
                    String message = "";
                    for(int i = 1; i < args.length; i++) {
                        message += args[i];
                        if(i < args.length - 1) message += " ";
                    }
                    
                    String name = ChatColor.BLUE + "[TD:" + args[0] + "] ";
                    getServer().broadcastMessage(name + ChatColor.WHITE + message);
                }
                else {
                    return false;
                }
            }
            return true;
        };
        
        getCommand("sos").setExecutor(sendMessage);
        getCommand("batsignal").setExecutor(sendMessage);
        getCommand("tdsay").setExecutor(broadcast);
    }
    
}
