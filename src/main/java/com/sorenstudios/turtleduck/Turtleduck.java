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

import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.command.CommandExecutor;
import org.bukkit.configuration.file.FileConfiguration;

public class Turtleduck extends JavaPlugin {
    
    @Override
    public void onEnable() {
    	// Save config if it isn't there already
    	this.saveDefaultConfig();
    	// Load config
    	FileConfiguration config = this.getConfig();
		
        getLogger().info("Loaded successfully!");
        
        CommandExecutor sendMessage = (sender, cmd, label, args) -> {
            if(args.length > 0) {
                sender.sendMessage("<Fluttershy> yay");
                return true;
            }
            return false;
        };
        
        getCommand("sos").setExecutor(sendMessage);
        getCommand("batsignal").setExecutor(sendMessage);
        getCommand("brrng").setExecutor(sendMessage);
    }
    
}
