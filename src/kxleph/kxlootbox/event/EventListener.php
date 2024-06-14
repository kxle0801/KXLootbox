<?php
/*
 * Github: https://github.com/kxle0801
 * Author: KxlePH
 */

declare(strict_types = 1);

namespace kxleph\kxlootbox\event;

use kxleph\kxlootbox\Main;

use kxleph\kxlootbox\utils\ItemUtils;
use kxleph\kxlootbox\utils\SoundUtils;
use kxleph\kxlootbox\utils\SourceUtils;
use kxleph\kxlootbox\inventory\LootboxMenu;

use pocketmine\event\Listener;

use pocketmine\event\block\BlockBreakEvent;

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
            
            $event->cancel();
            switch ($action) {
                case PlayerInteractEvent::LEFT_CLICK_BLOCK:
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
                    break;
                case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
                    if (!$config->get("preview")) return;
                    
                    LootboxMenu::send($player, $lootboxData);
                    SoundUtils::send($player, $sound->get("sound-Preview"));
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $inventory = $player->getInventory();
        $item = $inventory->getItemInHand();
        $tag = $item->getNamedTag();
        
        if ($tag->getTag(ItemUtils::TAG_LOOTBOX) && $tag->getTag(ItemUtils::TAG_LOOTBOX_IDENTIFIER)) $event->cancel();
    }    
}
