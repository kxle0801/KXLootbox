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

use pocketmine\item\Item;
use pocketmine\command\CommandSender;

use libs\CortexPE\Commando\BaseSubCommand;
use libs\CortexPE\Commando\args\RawStringArgument;

class KXLootboxCreate extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->registerArgument(0, new RawStringArgument("lootbox_name", true));
		$this->registerArgument(1, new RawStringArgument("identifier", true));
	}

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

		if (!$plugin->getServer()->isOp($sender->getName())) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
			return;
		}
	
		if (!isset($args["lootbox_name"], $args["identifier"])) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("sub-cmd-create-usage")));
			return;
		}

		$kxData = KXSourceUtils::getKXBoxData()->get($args["identifier"]);
		if (!is_array($kxData) || !isset($kxData['identifier']) || $kxData['identifier'] !== $args["identifier"]) {
			$inventory = $sender->getInventory();
			$contents = $inventory->getContents();
			$maxRewards = $config->get("lootbox-max-rewards");
			if (empty($contents) || (count($contents) + 1) <= $maxRewards) {
				$sender->sendMessage($config->get("prefix") . " " . str_replace("{max_rewards}", (string) $maxRewards, $message->get("base-cmd-NoContents")));
				return;
			}
	
			$lores = array_map(function(Item $item): string {
				return $item->getName();
			}, $contents);
	
			KXSourceUtils::saveKXBoxData($args["identifier"], ["identifier" => $args["identifier"], "name" => $args["lootbox_name"], "contents" => $contents, "lores" => $lores]);
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{name}", $args["lootbox_name"], str_replace("{identifier}", $args["identifier"], $message->get("base-cmd-Success"))));
			return;
		}
		$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-IdExist")));
	}	
}