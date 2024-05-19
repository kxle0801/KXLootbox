<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * For more information about the GNU Lesser General Public License and
 * how it applies to this software, please see the LICENSE file included
 * with this distribution or visit the GNU website at <https://www.gnu.org/>.
 * 
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\inventory;

use kxle\utils\KXItemUtils;
use kxle\utils\KXSoundUtils;
use kxle\utils\KXSourceUtils;

use pocketmine\player\Player;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;

class KXLootboxMenu {

    /**
     * @param Player $player
     * @param array $kxData
     * @return void
     */
	public static function send(Player $player, array $kxData): void {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
		$menu->setName("§r§c" . $kxData['name'] . "'s §r§8Preview");
        $inventory = $menu->getInventory();
        $sound = KXSourceUtils::getSounds();

        $contents = KXItemUtils::decodeContent($kxData['contents']);
        foreach ($contents as $content) if (is_string($content)) $inventory->addItem(KXItemUtils::decodeItem($content));
        KXSoundUtils::send($player, $sound->get("sound-Close"));

		$menu->setListener(InvMenu::readonly());
        $menu->send($player);
	}
}