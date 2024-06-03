<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\inventory;

use kxle\utils\ItemUtils;
use kxle\utils\SoundUtils;
use kxle\utils\SourceUtils;

use pocketmine\player\Player;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;

class LootboxMenu {

    /**
     * @param Player $player
     * @param array $lootboxData
     * @return void
     */
	public static function send(Player $player, array $lootboxData): void {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
		$menu->setName("§r§c" . $lootboxData['name'] . "'s §r§8Preview");
        $inventory = $menu->getInventory();
        $sound = SourceUtils::getSounds();

        $contents = ItemUtils::decodeContent($lootboxData['contents']);
        foreach ($contents as $content) if (is_string($content)) $inventory->addItem(ItemUtils::decodeItem($content));
        SoundUtils::send($player, $sound->get("sound-Close"));

		$menu->setListener(InvMenu::readonly());
        $menu->send($player);
	}
}