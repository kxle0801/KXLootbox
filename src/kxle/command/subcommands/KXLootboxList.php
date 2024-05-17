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

namespace kxle\command\subcommands;

use kxle\KXLootbox;

use kxle\utils\KXSourceUtils;
use kxle\utils\PermissionIds;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;

class KXLootboxList extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		$plugin = KXLootbox::getInstance();
		$config = $plugin->getConfig();
		$message = KXSourceUtils::getMessages();

		if (!$sender->hasPermission(PermissionIds::KXLOOTBOX_COMMAND)) {
	            $sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
	            return;
        	}
		
		$kxBoxData = KXSourceUtils::getKXBoxData()->getAll();
		$boxesInfo = [];
		foreach ($kxBoxData as $identifier => $data) {
			$name = $data['name'];
			$boxesInfo[$identifier] = $name;
		}

		if (empty($boxesInfo)) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base_cmd}", $config->get("base-cmd"), $message->get("sub-cmd-NoBoxes")));
			return;
		}

		$count = 1;
		$sender->sendMessage("§r§7Lists of created lootboxes:\n");
		foreach ($boxesInfo as $identifier => $name) {
			$sender->sendMessage(str_replace(["{count}", "{lootbox_name}", "{identifier}"], [$count, $name, $identifier], $config->get("sub-cmd-list-format")));
			$count++;
		}
	}
}
