<?php

namespace roi611\warp;

use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class Main extends PluginBase implements Listener {

    private $config;
    
    public function onEnable():void{

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
            "jotp" => false,
            //設定した場所にテレポートさせるか(true/false)
            "optp" => false,
            //opを指定した場所にテレポートさせるか(true/false)
            "X"   => 0,
            "Y"   => 4,
            "Z"   => 0,
            "World"   => "world"
            //テレポートさせる場所
        ));

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{

        if(!$sender instanceof Player) {
            $sender->sendMessage("ゲーム内で実行してください");
            return true;
        }

        if (isset($args[0])) {

            switch($args[0]){
            
                case "on":

                    $this->config->set("jotp", true);
                    $this->config->save(); 
                    $sender->sendMessage("§4[jotp]§r sptpを有効にしました");

                break;

                case "off":

                    $this->config->set("jotp", false);
                    $this->config->save(); 
                    $sender->sendMessage("§4[jotp]§r sptpを無効にしました");

                break;

                case "opon":

                    $this->config->set("optp", true);
                    $this->config->save(); 
                    $sender->sendMessage("§4[jotp]§r opのsptpを有効にしました");

                break;

                case "opoff":

                    $this->config->set("optp", false);
                    $this->config->save(); 
                    $sender->sendMessage("§4[jotp]§r opのsptpを無効にしました");

                break;

                case "set":

                    $p = $sender->getPosition();
                    $x = $p->getFloorX();
                    $y = $p->getFloorY();
                    $z = $p->getFloorZ();
                    $level = $sender->getWorld()->getDisplayName();
                    $this->config->set("X", $x);
                    $this->config->set("Y", $y);
                    $this->config->set("Z", $z);
                    $this->config->set("World", $level);
                    $this->config->save(); 
                    $sender->sendMessage("§4[jotp]§r {$x},{$y},{$z},{$level}にjotp地点を設定しました");

                break;
                
                case "help":
                    $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setをしてから実行にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");
                break;

                default:
                    $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setをしてから実行にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");

            }


        } else {
            $sender->sendMessage("/jotp on : JoinTeleportを有効にします(/join setをしてから実行にしてください)\n/jotp off : JoinTereleportを無効にします\n/jotp opon : opもJoin時にテレポートさせます\n/jotp onoff : opがJoin時にテレポートさせなくします\n/jotp set : スポーン地点を設定します");
        }

        return true;

    }



    public function onJoin(PlayerJoinEvent $event) {

        $player = $event->getPlayer();
        $jotp = $this->config->get("jotp");
        $joinop = $this->config->get("optp");
        $x = $this->config->get("X");
        $y = $this->config->get("Y");
        $z = $this->config->get("Z");

        if($jotp === true) {

            $level = Server::getInstance()->getWorldManager()->getWorldByName($this->config->get("World"));
            $pos = new Position((int)$x, (int)$y, (int)$z, $level);

            if($joinop === true) {

                $player->teleport($pos);

            } else {

                if(!(Server::getInstance()->isOp($player->getName()))){
                    $player->teleport($pos);
                }

            }

        }

    }



}
