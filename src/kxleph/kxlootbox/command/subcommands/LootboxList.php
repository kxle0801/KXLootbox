<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxleph\kxlootbox\command\subcommands;

use kxleph\kxlootbox\Main;

use kxleph\kxlootbox\utils\SoundUtils;
use kxleph\kxlootbox\utils\SourceUtils;
use kxleph\kxlootbox\utils\PermissionIds;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;

class LootboxList extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::KXLOOTBOX_LIST);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $aliasUsed
	 * @param array $args
	 * @return void
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
		$plugin = Main::getInstance();
		$config = $plugin->getConfig();
		$message = SourceUtils::getMessages();
		$sound = SourceUtils::getSounds();
		
		$lootboxData = SourceUtils::getLootboxData()->getAll();
		$looboxInfo = [];
		foreach ($lootboxData as $identifier => $data) {
			$name = $data['name'];
			$looboxInfo[$identifier] = $name;
		}

		if (empty($looboxInfo)) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $message->get("sub-cmd-NoBoxes")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$count = 1;
		$sender->sendMessage("§r§7Lists of created lootboxes:\n");
		foreach ($looboxInfo as $identifier => $name) {
			$sender->sendMessage(str_replace(["{count}", "{name}", "{identifier}"], [$count, $name, $identifier], $config->get("sub-cmd-list-format")));
			$count++;
		}
		SoundUtils::send($sender, $sound->get("sound-cmd-List"));
	}
}