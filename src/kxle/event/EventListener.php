<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxle\event;

use kxle\Main;

use kxle\utils\ItemUtils;
use kxle\utils\SoundUtils;
use kxle\utils\SourceUtils;
use kxle\inventory\LootboxMenu;

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
        
        if ($tag->getTag(ItemUtils::TAG_LOOTBOX) && $tag->getTag(ItemUtils::TAG_LOOTBOX_IDENTIFIER)) {
            $plugin = Main::getInstance();
		    $config = $plugin->getConfig();
            $message = SourceUtils::getMessages();
            $sound = SourceUtils::getSounds();
            
            $lootboxIdentifier = $tag->getTag(ItemUtils::TAG_LOOTBOX_IDENTIFIER)->getValue();
            $lootboxData = SourceUtils::getLootboxData()->get($lootboxIdentifier);

            if (!is_array($lootboxData)) return;

            $inventory = $player->getInventory();
            $inventorySize = $inventory->getSize();
            $inventoryItemsCount = count($inventory->getContents());
            $contents = ItemUtils::decodeContent($lootboxData['contents']);
            
            switch ($action) {
                case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
                    $inventorySpace = $config->get("lootbox-max-rewards");
                    if (($inventoryItemsCount + $inventorySpace) > $inventorySize) {
                        $player->sendMessage($config->get("prefix") . " " . $message->get("sub-cmd-NoSpace"));
                        SoundUtils::send($player, $sound->get("sound-InvalidAction"));
                        $event->cancel();
                        return;
                    }

                    shuffle($contents);

                    $maxRewards = 0;
                    foreach ($contents as $content) {
                        if (is_string($content)) {
                            $lootboxItem = ItemUtils::decodeItem($content);
                            $inventory->addItem($lootboxItem);
                            $maxRewards++;
                            
                            if ($maxRewards >= $config->get("lootbox-max-rewards")) break;
                        }
                    }
                    $player->sendMessage($config->get("prefix") . " " . str_replace("{name}", $lootboxData['name'], $message->get("lb-claim-Opened")));
                    $inventory->setItemInHand($item->setCount($item->getCount() - 1));
                    SoundUtils::send($player, $sound->get("sound-Claimed"));
                    $event->cancel();
                    break;
                case PlayerInteractEvent::LEFT_CLICK_BLOCK:
                    if (!$config->get("lootbox-preview")) {
                        $event->cancel();
                        return;
                    }

                    LootboxMenu::send($player, $lootboxData);
                    SoundUtils::send($player, $sound->get("sound-Preview"));
                    $event->cancel();
                    break;
                default:
                    break;
            }
        }
    }    
}