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

use pocketmine\data\bedrock\EnchantmentIdMap;

use pocketmine\item\Item;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\block\VanillaBlocks;

final class KXItemUtils {

    const TAG_KXBOX = "tag.kxbox";
    const TAG_KXBOX_IDENTIFIER = "tag.kxbox.id";

    const CHEST_TYPE = "chest.type";
    const ENDERCHEST_TYPE = "enderchest.type";

    const TYPE_CUSTOM_LORE = "custom.type";
    const TYPE_ITEMS_LORE = "items.type";

    /**
     * @param string $itemType
     * @param string $kxBoxName
     * @param integer $count
     * @param array[] $lores
     * @param boolean $isGlint
     * @return Item|null
     */
    public static function giveKXBoxItem(string $itemType, string $kxBoxName, string $identifier, int $count, array $lores = [], bool $isGlint = true): ?Item {
        $lootbox = $itemType === self::CHEST_TYPE ? VanillaBlocks::CHEST()->asItem() : VanillaBlocks::ENDER_CHEST()->asItem();
        if ($isGlint)  $lootbox->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(-1)));
        
        $lootbox->setCustomName($kxBoxName)->setCount($count)->setLore($lores);
        $lootbox->setNamedTag($lootbox->getNamedTag()->setString(self::TAG_KXBOX, $kxBoxName)->setString(self::TAG_KXBOX_IDENTIFIER, $identifier));
        return in_array($itemType, [self::CHEST_TYPE, self::ENDERCHEST_TYPE]) ? $lootbox : null;
    }    

    /**
     * @param array $contents
     * @return string
     */
    public static function encodeContent(array $contents): string {
        $kxBoxItems = [];
		foreach ($contents as $content) $kxBoxItems[] = self::encodeItem($content);
		return json_encode($kxBoxItems);
    }

    /**
     * @param string $kxBoxData
     * @return array|null
     */
    public static function decodeContent(string $kxBoxData): ?array {
		$kxBoxItems = json_decode($kxBoxData);
        if (!is_null($kxBoxItems)) return $kxBoxItems;
        return null;
    }

    /**
     * @param Item $item
     * @return string
     */
    public static function encodeItem(Item $item): string {
        return base64_encode(gzcompress(self::itemToJson($item)));
    }

    /**
     * @param string $item
     * @return Item
     */
    public static function decodeItem(string $item): Item {
        return self::jsonToItem(gzuncompress(base64_decode($item)));
    }

    /**
     * @param Item $item
     * @return string
     */
    public static function itemToJson(Item $item): string {
        return base64_encode(serialize((clone $item)->nbtSerialize()));
    }

    /**
     * @param string $json
     * @return Item
     */
    public static function jsonToItem(string $json): Item {
        return Item::nbtDeserialize(unserialize(base64_decode($json)));
    }
}