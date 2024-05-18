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

use kxle\utils\KXItemUtils;
use kxle\utils\KXSourceUtils;
use kxle\utils\PermissionIds;

use pocketmine\Server;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class KXLootboxGive extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::KXLOOTBOX_COMMAND_GIVE);
		$this->registerArgument(0, new RawStringArgument("player", true));
		$this->registerArgument(1, new RawStringArgument("identifier", true));
		$this->registerArgument(2, new IntegerArgument("amount", true));
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
	
		if (!isset($args["player"], $args["identifier"], $args["amount"])) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("sub-cmd-give-usage")));
			return;
		}

		if (!is_numeric($args["amount"]) || intval($args["amount"]) <= 0) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-InvalidAmount"));
			return;
		}
		
		$kxData = KXSourceUtils::getKXBoxData()->get($args["identifier"]);
		if (!is_array($kxData) || !isset($kxData['identifier']) || $kxData['identifier'] !== $args["identifier"]) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-NoId")));
			return;
		}		

		$player = $plugin->getServer()->getPlayerExact($args["player"]);
		if (is_null($player)) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{player_name}", $args["player"], $message->get("sub-cmd-OfflinePlayer")));
			return;
		}

		$inventory = $player->getInventory();
		$lootboxLore = $config->get("lootbox-lore");
		$customLores = [];
		foreach ($lootboxLore as $lines) {
			$kxBoxLines = [];
			foreach ($kxData['lores'] as $boxLines) {
				$kxBoxLines[] = $boxLines;
			}
			$line = str_replace(["{name}", "{rewards}", "{identifier}"], [$kxData['name'], implode("\n", $kxBoxLines), $kxData['identifier']], $lines);
			$customLores[] = $line;
		}		

		$customLore = implode("\n", $customLores);
		$lores = $config->get("lootbox-lore-type") === KXItemUtils::TYPE_CUSTOM_LORE ? explode("\n", $customLore) : $kxData['lores'];
		$item = KXItemUtils::giveKXBoxItem($config->get("lootbox-type"), $kxData['name'], $args["identifier"], $args["amount"], $lores, $config->get("lootbox-glint"));

		if (!$inventory->canAddItem($item)) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-NoSpace"));
			return;
		}

		$inventory->addItem($item);
		$player->sendMessage(str_replace(["{lootbox_name}", "{amount}"], [$kxData['name'], (string) $args["amount"]], $config->get("prefix") . " " . $message->get("sub-cmd-give-Success")));
	}	
}