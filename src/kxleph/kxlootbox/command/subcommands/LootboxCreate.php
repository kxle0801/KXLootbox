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

use pocketmine\player\Player;

use pocketmine\item\Item;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;

class LootboxCreate extends BaseSubCommand {

	/**
	 * @return void
	 */
	public function prepare(): void {
		$this->setPermission(PermissionIds::KXLOOTBOX_CREATE);
		$this->registerArgument(0, new RawStringArgument("name", true));
		$this->registerArgument(1, new RawStringArgument("identifier", true));
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

		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoConsole"));
			return;
		}

		if (!$plugin->getServer()->isOp($sender->getName())) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}
	
		if (!isset($args["name"], $args["identifier"])) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("sub-cmd-create-usage")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$lootboxData = SourceUtils::getLootboxData()->get($args["identifier"]);
		if (!is_array($lootboxData) || !isset($lootboxData['identifier']) || $lootboxData['identifier'] !== $args["identifier"]) {
			$inventory = $sender->getInventory();
			$contents = $inventory->getContents();
			$maxRewards = $config->get("lootbox-max-rewards");
			if (empty($contents) || (count($contents) + 1) <= $maxRewards) {
				$sender->sendMessage($config->get("prefix") . " " . str_replace("{max-rewards}", (string) $maxRewards, $message->get("base-cmd-NoContents")));
				SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
				return;
			}
	
			$lores = array_map(function(Item $item): string {
				return $item->getName();
			}, $contents);
	
			SourceUtils::saveLootboxData($args["identifier"], ["identifier" => $args["identifier"], "name" => $args["name"], "contents" => $contents, "lores" => $lores]);
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{name}", $args["name"], str_replace("{identifier}", $args["identifier"], $message->get("base-cmd-Success"))));
			SoundUtils::send($sender, $sound->get("sound-cmd-Create"));
			return;
		}
		$sender->sendMessage($config->get("prefix") . " " . str_replace("{identifier}", $args["identifier"], $message->get("sub-cmd-IdExist")));
		SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
	}	
}