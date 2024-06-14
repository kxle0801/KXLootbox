<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxleph\kxlootbox;

use kxleph\kxlootbox\event\EventListener;

use kxleph\kxlootbox\utils\SourceUtils;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\SingletonTrait;

use CortexPE\Commando\PacketHooker;
use muqsit\invmenu\InvMenuHandler;

class Main extends PluginBase {

    use SingletonTrait;

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        $virions = [
            PacketHooker::class => PacketHooker::isRegistered(),
            InvMenuHandler::class => InvMenuHandler::isRegistered()
        ];
        
        foreach ($virions as $virion => $isRegistered) if (!$isRegistered) $virion::register($this);

        SourceUtils::init();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}
