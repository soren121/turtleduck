package com.sorenstudios.samverify;

import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.configuration.file.FileConfiguration;
import org.bukkit.entity.Player;

import code.husky.mysql.MySQL;

import java.sql.Connection;
import java.sql.SQLException;

public final class SamVerify extends JavaPlugin {
    @Override
    public void onEnable() {
    	// Save config if it isn't there already
    	this.saveDefaultConfig();
    	// Load config
    	FileConfiguration config = this.getConfig();
    	
    	// Get MySQL info
    	String sqlUser = config.getString("mysql.user");
    	String sqlPass = config.getString("mysql.pass");
    	String sqlDatabase = config.getString("mysql.db");
    	String sqlHost = config.getString("mysql.host");
    	String sqlPort = config.getString("mysql.port");
    	
    	MySQL MySQL = new MySQL(this, sqlHost, sqlPort, sqlDatabase, sqlUser, sqlPass);
    	Connection c = null;
    	
		try {
			c = MySQL.openConnection();
		} catch (ClassNotFoundException | SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
        getLogger().info("[SamVerify] Loaded successfully!");
    }
    
    @Override
    public boolean onCommand(CommandSender sender, Command cmd, String label, String[] args) {
        if (cmd.getName().equalsIgnoreCase("register") && args.length == 1) {
            if (!(sender instanceof Player)) {
                sender.sendMessage("This command can only be run by a player.");
            }
            else {
                Player player = (Player) sender;
                player.sendMessage("Fluttershy says hello.");
            }
            
            return true;
        }
        
        return false;
    }
}
