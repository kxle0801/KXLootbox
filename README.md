## Overview
<h1>KXLootbox<img src="https://github.com/kxle0801/KXLootbox/blob/main/kxlootbox_icon.png" height="64" width="64" align="left"></img>&nbsp;</h1>
<br>
KXLootbox plugin for PocketMine-MP and inspired by Subscriber Suggestion.
<br>
This plugin allows you to make your own Lootbox in-game. Fully Configurable and much more!
<br>

## Features
- [x] Fully Configurable
- [x] Manages Lootbox In-Game
- [x] Togglable Preview Contents
- [x] Custom Command
- [x] And many more!

## Config
```yaml
# KXLootbox Configuration.

# Do not change the version. RECOMMENDED!
config-version: 0.1.0

# https://www.digminecraft.com/lists/color_list_pc.php
# Go to that site to get the preview of colors avaible in Minecraft.
# Use this '§' to declare a color.
# You can change this prefix as you want. Leave "" if don't want it.
prefix: "§r§8[§4KXL§coot§fbox§8]§r"

# Base command for executing the plugin command.
base-cmd: "kxleph" # ;>

# Base Command Description of your provided Base Command.
base-cmd-desc: "Creates a lootbox that stores your inventory items in it."

# Base Command Aliases instead of executing Base Command use this as shortcut.
# You can add more aliases as you want.
base-cmd-alias:
  - "lootbox"
  - "kxlb"
  - "lb"

# Base Command Usage which sends to sender when command is incorrect.
# {base-cmd} stands for your Base Command above. RECOMMENDED FORMAT!
base-cmd-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:create|delete|give|list]"

# Notifies when "create" action with Base Command is improperly executed.
sub-cmd-create-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:create] [string:lootbox_name] [string:identifier]"

# Notifies when "delete" action with Base Command is improperly executed.
sub-cmd-delete-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:delete] [string:identifier]"

# Sends when the "give" action with Base Command is improperly executed.
sub-cmd-give-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:give] [string:player_name] [string:identifier] [int:amount]"

# Sends when Base Command List executed by player with this format. RECOMMEND FORMAT!
sub-cmd-list-format: "§r§c{count}. §7Name: §c{lootbox_name}\n§7- Identifier: §c{identifier}§r\n"

# Max rewards that can player get in your lootbox.
# Ex.: Lootbox contains 8 items in it so then 3 items will be given by default.
# Note: Rewards are random.
lootbox-max-rewards: 3

# Item type of lootbox when giving it to player, TWO types are currently avaible. More SOON!
# Lootbox Type:
# "chest.type" for Chest
# "enderchest.type" for Ender Chest
lootbox-type: "chest.type"

# This accepts boolean only or TRUE|FALSE
# Adds display enchant that glint to Lootbox Item Type
lootbox-glint: true

# This option sets what to show in your lootbox lore.
# "custom.type" set custom lore as you want on lootbox-lore below.
# "items.type" sets as lore for every item name on the lootbox rewards.
lootbox-lore-type: "custom.type"

# Allows to preview contents of lootbox by Tap-Holding or Right-Clicking it.
lootbox-preview: true

# Custom lore info for your lootbox.
# tag:
# {name} lootbox name
# {rewards} shows all possible rewards of lootbox
# {identifier} shows what's lootbox identifier
lootbox-lore:
  - §r§7Place {name} §7anywhere to claim your rewards!
  - ""
  - §r§7(§aRight-Click to preview§7)
  - §r§7---
```

## Command
| **COMMAND** | **DESCRIPTION** | **PERMISSION** | **ALIASES** | **USAGE** |
| --- | --- | --- | --- | --- |
| `/kxleph` | `This executes KXLootbox plugin command.` | *`perm.kxbox.command`* | `[lootbox, kxlb, lb]` | `[create, give, delete, list]` |
| `/kxleph create` | `Creates a lootbox that stores your inventory items in it.` | *`N/A`* | *`N/A`* | `[lootbox_name:identifier]` |
| `/kxleph give` | `Gives player a lootbox with specified lootbox identifier` | *`N/A`* | *`N/A`* | `[player_name:identifier:amount]` |
| `/kxleph delete` | `Deletes existing lootbox with specified lootbox identifier` | *`N/A`* | *`N/A`* | `[identifier]` |
| `/kxleph list` | `Lists all created lootbox with name and identifier.` | *`N/A`* | *`N/A`* | *`N/A`* |

REMEMBER: You can customize command name, description, aliases and usage information on your `plugin_data/KXLootbox/config.yml` file, Use it wisely!

## Virions
- [Commando](https://github.com/CortexPE/Commando) (CortexPE)
- [InvMenu](https://github.com/muqsit/InvMenu) (Muqsit)

## Support
- Need help or Report Bugs and Errors? [Contact Us](https://discord.gg/vhnRSH7k) (Our Discord Community)

## 📜TODO
- [x] Animations per lootbox
- [x] Titles or Popups
- [x] Remove specific item on lootbox
- [x] Add Chances for each items
- [x] Editable lootbox details

## Get In Touch
[![YOUTUBE](https://img.shields.io/badge/KxlePH-white?logo=youtube&logoColor=red&label=Youtube&labelColor=white&color=red)](https://www.youtube.com/@kxle-ph)
[![DISCORD](https://img.shields.io/badge/Elysium_Community-black?logo=discord&logoColor=white&label=Discord&labelColor=blue&color=white)](https://discord.gg/vhnRSH7k)
<br>
Subscribe for more! ❤️
