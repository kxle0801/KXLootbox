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
 * Author: KxlePH.
 */

declare(strict_types = 1);

namespace kxle;

use kxle\event\EventListener;

use kxle\utils\KXSourceUtils;

use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\data\bedrock\EnchantmentIdMap;

use pocketmine\plugin\PluginBase;

use libs\CortexPE\Commando\PacketHooker;
use libs\muqsit\invmenu\InvMenuHandler;

class KXLootbox extends PluginBase {

    /** @var KXLootbox */
    private static self $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        if (!PacketHooker::isRegistered()) PacketHooker::register($this);
        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);

        KXSourceUtils::init();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        EnchantmentIdMap::getInstance()->register(-1, new Enchantment("Glint", -1, ItemFlags::ALL, ItemFlags::NONE, 1));
    }

    /**
     * @return KXLootbox
     */
    public static function getInstance(): self {
        return self::$instance;
    }
}
