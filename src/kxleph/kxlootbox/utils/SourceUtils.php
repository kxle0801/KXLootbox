<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxleph\kxlootbox\utils;

use kxleph\kxlootbox\Main;

use kxleph\kxlootbox\command\LootboxCommand;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\data\bedrock\EnchantmentIdMap;

final class SourceUtils {

    /** @var Config */
    private static Config $lootboxes;

    /** @var Config */
    private static Config $messages;

    /** @var Config */
    private static Config $sounds;

    /**
     * @return void
     */
	public static function init(): void {
        $plugin = Main::getInstance();
		$config = $plugin->getConfig();
        
        $plugin->saveDefaultConfig();
        self::$lootboxes = new Config(self::getDataFolder() . "lootboxes.yml", Config::YAML);

        $plugin->saveResource("messages.yml");
        self::$messages = new Config(self::getDataFolder() . "messages.yml", Config::YAML);

        $plugin->saveResource("sounds.yml");
        self::$sounds = new Config(self::getDataFolder() . "sounds.yml", Config::YAML);

        EnchantmentIdMap::getInstance()->register(-1, new Enchantment("Glint", -1, ItemFlags::ALL, ItemFlags::NONE, 1));

        $oldVersion = $config->exists("config-version") ? $config->get("config-version") : null;
        if (!$oldVersion || version_compare("0.1.0", $oldVersion) > 0) {
            $plugin->getLogger()->notice(TF::RESET . TF::GOLD . "You're using an older version of your configuration file. Updating the Configuration...");
            $plugin->getLogger()->notice(TF::RESET . TF::GOLD . "The config_old.yml file contains the previous configuration file.");

            copy(self::getDataFolder() . "config.yml", self::getDataFolder() . "config_old.yml");
            $plugin->reloadConfig();
        }

        $plugin->getServer()->getCommandMap()->register("KXLootbox", 
            new LootboxCommand($plugin,
                $config->exists("base-cmd") ? $config->get("base-cmd") : "lootbox",
                $config->exists("base-cmd-desc") ? $config->get("base-cmd-desc") : "Creates a lootbox that stores your inventory items in it.",
                $config->exists("base-cmd-alias") ? $config->get("base-cmd-alias") : ["lbox"]
            )
        );
	}

    /**
     * @return string
     */
    public static function getDataFolder(): string {
		return Main::getInstance()->getDataFolder();
	}

    /**
     * Gets lootbox YML.
     * 
     * @return Config
     */
    public static function getLootboxData(): Config {
        return self::$lootboxes;
    }

    /**
     * Gets Messages YML.
     * 
     * @return Config
     */
    public static function getMessages(): Config {
        return self::$messages;
    }

    /**
     * Gets Sounds YML.
     * 
     * @return Config
     */
    public static function getSounds(): Config {
        return self::$sounds;
    }

    /**
     * Saves created lootbox data to YML.
     * 
     * @param string $lootboxIdentifier
     * @param array $contents
     * @return void
     */
    public static function saveLootboxData(string $lootboxIdentifier, array $contents): void {
        self::getLootboxData()->set($lootboxIdentifier, [
            "identifier" => TF::clean($contents['identifier']),
            "name" => $contents['name'],
            "contents" => ItemUtils::encodeContent($contents['contents']),
            "lores" => $contents['lores']
        ]);
        self::getLootboxData()->save();
    }
}