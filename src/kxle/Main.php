<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle;

use kxle\event\EventListener;

use kxle\utils\SourceUtils;

use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\data\bedrock\EnchantmentIdMap;

use pocketmine\plugin\PluginBase;

use CortexPE\Commando\PacketHooker;
use muqsit\invmenu\InvMenuHandler;

class Main extends PluginBase {

    /** @var self */
    private static self $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $virions = [
            PacketHooker::class => PacketHooker::isRegistered(),
            InvMenuHandler::class => InvMenuHandler::isRegistered()
        ];
        
        foreach ($virions as $virion => $isRegistered) if (!$isRegistered) $virion::register($this);

        SourceUtils::init();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        EnchantmentIdMap::getInstance()->register(-1, new Enchantment("Glint", -1, ItemFlags::ALL, ItemFlags::NONE, 1));
    }

    /**
     * @return self
     */
    public static function getInstance(): self {
        return self::$instance;
    }
}