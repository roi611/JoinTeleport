<?php

namespace roi611\warp;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;

class Main extends PluginBase implements Listener {

    public function onEnable() {

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "jotp" => false,
            //設定した場所にテレポートさせるか(true/false)
            "optp" => false,
            //opを指定した場所にテレポートさせるか(true/false)
            "X"   => "",
            "Y"   => "",
            "Z"   => "",
            "Level"   => ""
            //テレポートさせる場所
        ));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{

        if ($sender instanceof Player) {
        if (isset($args[0])) {
        switch($args[0]){
        
            case "on":
                $this->config->set("jotp", true);
                $this->config->save(); 
                $sender->sendMessage("§4[jotp]§r sptpを有効にしました");
                return true;
            break;

            case "off":
                $this->config->set("jotp", false);
                $this->config->save(); 
                $sender->sendMessage("§4[jotp]§r sptpを無効にしました");
                return true;
            break;

            case "opon":
                $this->config->set("optp", true);
                $this->config->save(); 
                $sender->sendMessage("§4[jotp]§r opのsptpを有効にしました");
                return true;
            break;

            case "opoff":
                $this->config->set("optp", false);
                $this->config->save(); 
                $sender->sendMessage("§4[jotp]§r opのsptpを無効にしました");
                return true;
            break;

            case "set":
               $player =  $sender->getServer()->getPlayer($sender->getName());
               $x = $player->getFloorX();
               $y = $player->getFloorY();
               $z = $player->getFloorZ();
               $level = $player->getLevel()->getName();
               $this->config->set("X", $x);
               $this->config->set("Y", $y);
               $this->config->set("Z", $z);
               $this->config->set("Level", $level);
               $this->config->save(); 
               $sender->sendMessage("§4[jotp]§r {$x},{$y},{$z},{$level}にjotp地点を設定しました");
               return true;
            break;
            
            case "help":
               $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setのしてから有効にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");
               return true;
               break;
            default:
            $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setのしてから有効にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");
            return true;
            break;
        }
        return true;
    } else {
        $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setのしてから有効にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");
    }
    return true;
} else {
    $sender->sendMessage("ゲーム内で実行してください");
}
}
    public function onJoin(PlayerJoinEvent $event) {

        $player = $event->getPlayer();
        $jotp = $this->config->get("jotp");
        $joinop = $this->config->get("optp");
        $x = $this->config->get("X");
        $y = $this->config->get("Y");
        $z = $this->config->get("Z");
        $level = Server::getInstance()->getLevelByName($this->config->get("Level"));
        $pos = new Position($x, $y, $z, $level);
        if($jotp == true) {
        if($joinop == true) {
        if($player->isOp()) {
        $player->teleport($pos);
            } else {
                $player->teleport($pos);
            }
        } 
        }
    }
}
