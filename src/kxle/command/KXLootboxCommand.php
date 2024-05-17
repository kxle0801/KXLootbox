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

namespace kxle\command;

use kxle\KXLootbox;

use kxle\utils\KXSourceUtils;
use kxle\utils\PermissionIds;

use kxle\command\subcommands\KXLootboxGive;
use kxle\command\subcommands\KXLootboxList;
use kxle\command\subcommands\KXLootboxCreate;
use kxle\command\subcommands\KXLootboxDelete;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

class KXLootboxCommand extends BaseCommand {

	/**
	 * @param Plugin $plugin
	 * @param string $name
	 * @param string $description
	 * @param array $aliases
	 */
	public function __construct(
		Plugin $plugin,
		string $name,
		string $description = "",
		array $aliases = []
		) {
        parent::__construct($plugin, $name, $description, $aliases);
    }

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::KXLOOTBOX_COMMAND);
		$this->registerSubCommand(new KXLootboxCreate("create"));
		$this->registerSubCommand(new KXLootboxDelete("delete"));
		$this->registerSubCommand(new KXLootboxGive("give"));
		$this->registerSubCommand(new KXLootboxList("list"));
	}

	/**
	 * @return void
	 */
	public function getPermission(): void {}

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

		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoConsole"));
			return;
		}
		
		if (!$plugin->getServer()->isOp($sender->getName())) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
			return;
		}
		$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("base-cmd-usage")));
	}	
}