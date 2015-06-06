package com.sorenstudios.turtleduck;

import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.configuration.file.FileConfiguration;
import org.bukkit.entity.Player;

public final class Turtleduck extends JavaPlugin {
    @Override
    public void onEnable() {
    	// Save config if it isn't there already
    	this.saveDefaultConfig();
    	// Load config
    	FileConfiguration config = this.getConfig();
		
        getLogger().info("Loaded successfully!");
    }
    
    @Override
    public boolean onCommand(CommandSender sender, Command cmd, String label, String[] args) {
        String command = cmd.getName();
        if ((command.equalsIgnoreCase("sos") || command.equalsIgnoreCase("batsignal")) && args.length > 0) {
            Player player = (Player) sender;
            player.sendMessage("<Fluttershy> yay");
            
            return true;
        }
        
        return false;
    }
}
