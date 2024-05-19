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

namespace kxle\utils;

use kxle\KXLootbox;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;

final class KXSoundUtils {
    
    /**
     * @param CommandSender $receiver
     * @param string $sound
     * @param int $volume
     * @param int $pitch
     */
    public static function send(CommandSender $receiver, string $sound, $volume = 1, $pitch = 1): void {
        if ($receiver instanceof ConsoleCommandSender) return;

        $plugin = KXLootbox::getInstance();
		$config = $plugin->getConfig();
        if (!$config->get("allow-sounds")) return;

        $location = $receiver->getLocation();
        $receiver->getNetworkSession()->sendDataPacket(PlaySoundPacket::create(
            $sound,
            $location->getX(),
            $location->getY(),
            $location->getZ(),
            $volume,
            $pitch
        ));
    }
}