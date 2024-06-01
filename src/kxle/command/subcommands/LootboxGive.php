<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\command\subcommands;

use kxle\Main;

use kxle\utils\ItemUtils;
use kxle\utils\SoundUtils;
use kxle\utils\SourceUtils;
use kxle\utils\PermissionIds;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;

class LootboxGive extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::LOOTBOX_COMMAND_GIVE);
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
		$plugin = Main::getInstance();
		$config = $plugin->getConfig();
		$message = SourceUtils::getMessages();
		$sound = SourceUtils::getSounds();
	
		if (!isset($args["player"], $args["identifier"], $args["amount"])) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("sub-cmd-give-usage")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		if (!is_numeric($args["amount"]) || intval($args["amount"]) <= 0) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-InvalidAmount"));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}
		
		$LootboxData = SourceUtils::getLootboxData()->get($args["identifier"]);
		if (!is_array($LootboxData) || !isset($LootboxData['identifier']) || $LootboxData['identifier'] !== $args["identifier"]) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-NoId")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}		

		$player = $plugin->getServer()->getPlayerExact($args["player"]);
		if (is_null($player)) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{player-name}", $args["player"], $message->get("sub-cmd-OfflinePlayer")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$inventory = $player->getInventory();
		$lootboxLore = $config->get("lore");
		$customLores = [];
		foreach ($lootboxLore as $lines) {
			$loreLines = [];
			foreach ($LootboxData['lores'] as $boxLines) {
				$loreLines[] = $boxLines;
			}
			$line = str_replace(["{name}", "{rewards}", "{identifier}"], [$LootboxData['name'], implode("\n", $loreLines), $LootboxData['identifier']], $lines);
			$customLores[] = $line;
		}		

		$customLore = implode("\n", $customLores);
		$lores = $config->get("type") === ItemUtils::TYPE_CUSTOM_LORE ? explode("\n", $customLore) : $LootboxData['lores'];
		$item = ItemUtils::giveLootboxItem($config->get("type"), $LootboxData['name'], $args["identifier"], $args["amount"], $lores, $config->get("lootbox-glint"));

		if (!$inventory->canAddItem($item)) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-NoSpace"));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$inventory->addItem($item);
		$player->sendMessage(str_replace(["{name}", "{amount}"], [$LootboxData['name'], (string) $args["amount"]], $config->get("prefix") . " " . $message->get("sub-cmd-give-Success")));
		SoundUtils::send($sender, $sound->get("sound-cmd-Give"));
	}	
}