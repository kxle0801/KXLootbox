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

namespace kxle\utils;

use kxle\KXLootbox;
use kxle\command\KXLootboxCommand;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

final class KXSourceUtils {

    /** @var Config */
    private static Config $kxLootbox;

    /** @var Config */
    private static Config $messages;

    /** @var Config */
    private static Config $sounds;

    /**
     * @return void
     */
	public static function init(): void {
        $plugin = KXLootbox::getInstance();
		$config = $plugin->getConfig();
        
        $plugin->saveDefaultConfig();
        self::$kxLootbox = new Config(self::getDataFolder() . "kxLootbox.yml", Config::YAML);

        $plugin->saveResource("messages.yml");
        self::$messages = new Config(self::getDataFolder() . "messages.yml", Config::YAML);

        $plugin->saveResource("sounds.yml");
        self::$sounds = new Config(self::getDataFolder() . "sounds.yml", Config::YAML);

        $oldVersion = $config->exists("config-version") ? $config->get("config-version") : null;
        if (!$oldVersion || version_compare("0.1.0", $oldVersion) > 0) {
            $plugin->getLogger()->notice(TF::RESET . TF::GOLD . "You're using an older version of your configuration file. Updating the Configuration...");
            $plugin->getLogger()->notice(TF::RESET . TF::GOLD . "The config_old.yml file contains the previous configuration file.");

            copy(self::getDataFolder() . "config.yml", self::getDataFolder() . "config_old.yml");
            $plugin->reloadConfig();
        }

        $plugin->getServer()->getCommandMap()->register(get_class($plugin), 
            new KXLootboxCommand($plugin,
                $config->exists("base-cmd") ? $config->get("base-cmd") : "kxlootbox",
                $config->exists("base-cmd-desc") ? $config->get("base-cmd-desc") : "Creates a lootbox that stores your inventory items in it.",
                $config->exists("base-cmd-alias") ? $config->get("base-cmd-alias") : ["kxlb"]
            )
        );
	}

    /**
     * @return string
     */
    public static function getDataFolder(): string {
		return KXLootbox::getInstance()->getDataFolder();
	}

    /**
     * Gets lootbox YML.
     * 
     * @return Config
     */
    public static function getKXBoxData(): Config {
        return self::$kxLootbox;
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
     * @param string $kxBoxIdentifier
     * @param array $contents
     * @return void
     */
    public static function saveKXBoxData(string $kxBoxIdentifier, array $contents): void {
        self::getKXBoxData()->set($kxBoxIdentifier, [
            "identifier" => TF::clean($contents['identifier']),
            "name" => $contents['name'],
            "contents" => KXItemUtils::encodeContent($contents['contents']),
            "lores" => $contents['lores']
        ]);
        self::getKXBoxData()->save();
    }
}