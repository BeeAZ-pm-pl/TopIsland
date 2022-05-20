<?php
    
namespace BeeAZ\TopIsland;
    
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerJoinEvent;
    
class TopListener implements Listener {

  public $plugin;

  public function __construct($plugin) {
   $this->plugin = $plugin;
  }
        
  public function onPlayerJoin(PlayerJoinEvent $event) {
   $name = strtolower($event->getPlayer()->getName());
   $user = $this->plugin->users->query("SELECT * FROM `users` WHERE `nickname` = '$name'")->fetchArray(SQLITE3_ASSOC);
   if($user === false) {
   $this->plugin->users->query("INSERT INTO `users`(`nickname`) VALUES('$name')");
   }
 }
  public function onPlace(BlockPlaceEvent $event) {
   $name = strtolower($event->getPlayer()->getName());
   $this->plugin->users->query("UPDATE `users` SET `place` = place + 1 WHERE `nickname` = '$name'");
  }
}