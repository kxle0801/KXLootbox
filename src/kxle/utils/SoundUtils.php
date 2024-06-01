<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\utils;

use kxle\Main;

use pocketmine\player\Player;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;

final class SoundUtils {
    
    /**
     * @param CommandSender $receiver
     * @param string $sound
     * @param int $volume
     * @param int $pitch
     */
    public static function send(CommandSender $receiver, string $sound, $volume = 1, $pitch = 1): void {
        if ($receiver instanceof ConsoleCommandSender) return;

        $plugin = Main::getInstance();
		$config = $plugin->getConfig();
        if (!$config->get("allow-sounds")) return;

        if ($receiver instanceof Player) {
            $location = $receiver->getLocation();
            $receiver->getNetworkSession()->sendDataPacket(PlaySoundPacket::create(
                $sound,
                $location->getX(),
                $location->getY(),
                $location->getZ(),
                $volume,
                $pitch
            ));
        }
    }
}