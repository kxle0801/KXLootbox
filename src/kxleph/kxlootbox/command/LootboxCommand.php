<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxleph\kxlootbox\command;

use kxleph\kxlootbox\Main;

use kxleph\kxlootbox\utils\SoundUtils;
use kxleph\kxlootbox\utils\SourceUtils;
use kxleph\kxlootbox\utils\PermissionIds;

use kxleph\kxlootbox\command\subcommands\LootboxGive;
use kxleph\kxlootbox\command\subcommands\LootboxList;
use kxleph\kxlootbox\command\subcommands\LootboxCreate;
use kxleph\kxlootbox\command\subcommands\LootboxDelete;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

class LootboxCommand extends BaseCommand {

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

		$config = Main::getInstance()->getConfig();
		$message = SourceUtils::getMessages();

		$this->setPermissionMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
		$this->registerSubCommand(new LootboxCreate("create"));
		$this->registerSubCommand(new LootboxDelete("delete"));
		$this->registerSubCommand(new LootboxGive("give"));
		$this->registerSubCommand(new LootboxList("list"));
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
		$plugin = Main::getInstance();
		$config = $plugin->getConfig();
		$message = SourceUtils::getMessages();
		$sound = SourceUtils::getSounds();

		if (!$sender instanceof Player) {
			$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoConsole"));
			return;
		}

		if ($plugin->getServer()->isOp($sender->getName())) {
			$sender->sendMessage($config->get("prefix") . " " . str_replace("{base-cmd}", $config->get("base-cmd"), $config->get("base-cmd-usage")));
			SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
			return;
		}

		$sender->sendMessage($config->get("prefix") . " " . $message->get("base-cmd-NoPerm"));
		SoundUtils::send($sender, $sound->get("sound-InvalidAction"));
	}	
}