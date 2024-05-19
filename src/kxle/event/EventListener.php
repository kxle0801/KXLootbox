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

namespace kxle\event;

use kxle\KXLootbox;

use kxle\utils\KXItemUtils;
use kxle\utils\KXSoundUtils;
use kxle\utils\KXSourceUtils;
use kxle\inventory\KXLootboxMenu;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class EventListener implements Listener {

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event): void {
        $action = $event->getAction();
        $player = $event->getPlayer();
        $item = $event->getItem();
        $tag = $item->getNamedTag();
        
        if ($tag->getTag(KXItemUtils::TAG_KXBOX) && $tag->getTag(KXItemUtils::TAG_KXBOX_IDENTIFIER)) {
            $plugin = KXLootbox::getInstance();
		    $config = $plugin->getConfig();
            $message = KXSourceUtils::getMessages();
            $sound = KXSourceUtils::getSounds();
            
            $kxBoxIdentifier = $tag->getTag(KXItemUtils::TAG_KXBOX_IDENTIFIER)->getValue();
            $kxData = KXSourceUtils::getKXBoxData()->get($kxBoxIdentifier);

            if (!is_array($kxData)) return;

            $inventory = $player->getInventory();
            $inventorySize = $inventory->getSize();
            $inventoryItemsCount = count($inventory->getContents());
            $contents = KXItemUtils::decodeContent($kxData['contents']);
            
            switch ($action) {
                case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
                    $inventorySpace = $config->get("lootbox-max-rewards");
                    if (($inventoryItemsCount + $inventorySpace) > $inventorySize) {
                        $player->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-NoSpace"));
                        KXSoundUtils::send($sender, $sound->get("sound-InvalidAction"));
                        $event->cancel();
                        return;
                    }

                    shuffle($contents);

                    $maxRewards = 0;
                    foreach ($contents as $content) {
                        if (is_string($content)) {
                            $kxBoxItem = KXItemUtils::decodeItem($content);
                            $inventory->addItem($kxBoxItem);
                            $maxRewards++;
                            
                            if ($maxRewards >= $config->get("lootbox-max-rewards")) break;
                        }
                    }
                    $player->sendMessage($config->get("prefix") . " " . str_replace("{lootbox_name}", $kxData['name'], $message->get("lb-claim-Opened")));
                    $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                    KXSoundUtils::send($sender, $sound->get("sound-Claimed"));
                    $event->cancel();
                    break;
                case PlayerInteractEvent::LEFT_CLICK_BLOCK:
                    if (!$config->get("lootbox-preview")) return;

                    KXLootboxMenu::send($player, $kxData);
                    KXSoundUtils::send($sender, $sound->get("sound-Preview"));
                    $event->cancel();
                    break;
                default:
                    break;
            }
        }
    }    
}