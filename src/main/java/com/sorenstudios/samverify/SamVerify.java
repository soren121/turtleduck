package com.sorenstudios.samverify;

import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.entity.Player;

public final class SamVerify extends JavaPlugin {
    @Override
    public boolean onCommand(CommandSender sender, Command cmd, String label, String[] args) {
        if (cmd.getName().equalsIgnoreCase("register")) {
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
