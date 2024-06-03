<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\command\subcommands;

use kxle\Main;

use kxle\utils\SoundUtils;
use kxle\utils\SourceUtils;
use kxle\utils\PermissionIds;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;

class LootboxDelete extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::KXLOOTBOX_DELETE);
		$this->registerArgument(0, new RawStringArgument("identifier", true));
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
	
		if (!isset($args["identifier"])) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("sub-cmd-delete-usage")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$lootboxData = SourceUtils::getLootboxData()->get($args["identifier"]);
		if (!is_array($lootboxData) || !isset($lootboxData['identifier']) || $lootboxData['identifier'] !== $args["identifier"]) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-NoId")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		SourceUtils::getLootboxData()->remove($args["identifier"]);
		$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-Removed")));
		SoundUtils::send($sender, $sound->get("sound-cmd-Delete"));
	}
}