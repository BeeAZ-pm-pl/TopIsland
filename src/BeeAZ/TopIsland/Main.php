<?php

namespace BeeAZ\TopIsland;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use SQLite3;

class Main extends PluginBase implements Listener {
        
  public $config;
  
  public $user;
        
  public function onEnable(): void {
   $folder = $this->getDataFolder();
   $this->saveResource('config.yml');
   $this->config = new Config($folder.'config.yml', Config::YAML);
   $this->users = new \SQLite3($folder.'topisland.db');
   $this->users->exec("CREATE TABLE IF NOT EXISTS users (nickname TEXT PRIMARY KEY NOT NULL, place INTEGER default 0 NOT NULL);");
   $this->getServer()->getPluginManager()->registerEvents(new TopListener($this), $this);
   }
        
   public function onCommand(CommandSender $player, Command $cmd, $label, array $args) : bool{
   if($cmd->getName() === "topisland"){
   if($player instanceof Player){
   $type = "place";
   $player->sendMessage("§c=======§e Top Island §c======");
   $player->sendMessage($this->sort($type));
        }
   }else{
   $player->sendMessage("Please Use Command In Game");
   }
   return true;
   }
   
  public function sort($type) {
  $count = $this->config->get('TopCount');
  $top = $this->users->query("SELECT nickname,$type FROM `users` ORDER BY $type DESC LIMIT $count");
  $list = "";
   while($element = $top->fetchArray(SQLITE3_ASSOC))
   $list .= str_replace(['{player}', '{value}'], [$element['nickname'], $element[$type]], $this->config->get('TopElement'))."\n";
   return $list;
   }
}
