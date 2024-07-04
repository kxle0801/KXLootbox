<div align="center">
  <img src="https://github.com/kxle0801/KXLootbox/blob/main/kxlootbox_icon.png" height="128" width="128" align="center"></img>
  <br><br>
  <h1> 📦 KXLootbox 📦 </h1>
  <a href="https://poggit.pmmp.io/p/KXLootbox"><img src="https://poggit.pmmp.io/shield.state/KXLootbox"></a>
  <a href="https://poggit.pmmp.io/p/KXLootbox"><img src="https://poggit.pmmp.io/shield.api/KXLootbox"></a>
  <a href="https://poggit.pmmp.io/p/KXLootbox"><img src="https://poggit.pmmp.io/shield.dl.total/KXLootbox"></a>
  <a href="https://poggit.pmmp.io/p/KXLootbox"><img src="https://poggit.pmmp.io/shield.dl/KXLootbox"></a>

  <p>
    KXLootbox plugin for PocketMine-MP, Inspired on our Community Plugin Suggestions.
  </p>
</div>

## Features
- 🟢 Fully Configurable
- 🟢 Manages Lootbox In-Game
- 🟢 Togglable Preview Contents
- 🟢 Custom Command
- 🟢 And many more!

## Support ❤️
- Need help or Report Bugs and Errors? [Contact Us](https://discord.gg/vhnRSH7k) (Our Discord Community)

# Config
```yaml
# Do not change the version. RECOMMENDED!
config-version: 0.1.1

# https://www.digminecraft.com/lists/color_list_pc.php
# Go to that site to get the preview of colors avaible in Minecraft.
# Use this '§' to declare a color.
# You can change this prefix as you want. Leave "" if don't want it.
prefix: "cr§8[§4KXL§coot§fbox§8]§r"

# Base command for executing the plugin command.
base-cmd: "lootbox"

# Base Command Description of your provided Base Command.
base-cmd-desc: "Creates a lootbox that stores your inventory items in it."

# Base Command Aliases instead of executing Base Command use this as shortcut.
# You can add more aliases as you want.
base-cmd-alias:
  - "lootbox"
  - "box"
  - "lb"

# Base Command Usage which sends to sender when command is incorrect.
# {base-cmd} stands for your Base Command above. RECOMMENDED FORMAT!
base-cmd-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:create|delete|give|list]"

# Notifies when "create" action with Base Command is improperly executed.
sub-cmd-create-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:create] [string:lootbox-name] [string:identifier]"

# Notifies when "delete" action with Base Command is improperly executed.
sub-cmd-delete-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:delete] [string:identifier]"

# Sends when the "give" action with Base Command is improperly executed.
sub-cmd-give-usage: "§r§7Usage: §r§c/{base-cmd} §7[action:give] [string:player-name] [string:identifier] [int:amount]"

# Sends when Base Command List executed by player with this format. RECOMMEND FORMAT!
sub-cmd-list-format: "§r§c{count}. §7Name: §c{name}\n§7- Identifier: §c{identifier}§r\n"

# Max rewards that can player get in your lootbox.
# Ex.: Lootbox contains 8 items in it so then 3 items will be given by default.
# Note: Rewards are random.
lootbox-max-rewards: 3

# Item type of lootbox when giving it to player, TWO types are currently avaible. More SOON!
# Types:
# "chest.type" for Chest
# "enderchest.type" for Ender Chest
type: "chest.type"

# This accepts boolean only or TRUE|FALSE
# Adds display enchant that glint to Lootbox Item Type
glint: true

# This option sets what to show in your lootbox lore.
# "custom.type" set custom lore as you want on lootbox-lore below.
# "items.type" sets as lore for every item name on the lootbox rewards.
lore-type: "custom.type"

# Allows to preview contents of lootbox by Tap-Holding or Right-Clicking it.
preview: true

# Custom lore info for your lootbox.
# tag:
# {name} lootbox name
# {rewards} shows all possible rewards of lootbox
# {identifier} shows what's lootbox identifier
lore:
  - "§r{identifier}"
  - §r§7Place this anywhere to claim {name} Lootbox§7.
  - "§r"
  - "§r§l§c** POSSIBLE REWARDS **"
  - "§r{rewards}"
  - "§r"
  - §r§8(§7Left-Click to §cclaim§8)
  - §r§8(§7Right-Click to §cpreview§8)
  - §r§7---

# Setting this to true enables to add a sound along with their action.
# You can modify sounds at sounds.yml
allow-sounds: true
```

# Commands
| **Command** | **Description** | **Permission** | **Aliases** | **Usage** | **Default** |
| --- | --- | --- | --- | --- | --- |
| `/lootbox` | This executes KXLootbox plugin command. | *`permission.lootbox.command`* | `[lootbox, box, lb]` | `[create, give, delete, list]` | op |
| `/lootbox create` | Creates a lootbox that stores your inventory items in it. | *`N/A`* | *`N/A`* | `[lootbox_name:identifier]` | op |
| `/lootbox give` | Gives player a lootbox with specified lootbox identifier | *`N/A`* | *`N/A`* | `[player_name:identifier:amount]` | op |
| `/lootbox delete` | Deletes existing lootbox with specified lootbox identifier` | *`N/A`* | *`N/A`* | `[identifier]` | op |
| `/lootbox list` | Lists all created lootbox with name and identifier. | *`N/A`* | *`N/A`* | *`N/A`* | op |

REMEMBER: You can customize command name, description, aliases and usage information on your `plugin_data/KXLootbox/config.yml` file, Use it wisely!

## Demo
<h1>Video Demo<img src="https://github.com/kxle0801/KXLootbox/blob/main/KXLootbox%20Thumbnail.png" height="72" width="128" align="left"></img></h1><br><br>

- Check Here: [Demo](https://youtu.be/9ydqS1pMYpk) (KxlePH)

# Download
- Download [Here](https://poggit.pmmp.io/r/238766/KXLootbox_dev-12.phar).

# TODO
- 🔴 Animations per lootbox
- 🔴 Titles or Popups
- 🟢 Remove specific item on lootbox
- 🔴 Add Chances for each items
- 🔴 Editable lootbox details
- 🟢 Add Sounds

## Virions
- [Commando](https://github.com/CortexPE/Commando) (CortexPE)
- [InvMenu](https://github.com/muqsit/InvMenu) (Muqsit)

## Get In Touch
[![YOUTUBE](https://img.shields.io/badge/KxlePH-white?logo=youtube&logoColor=red&label=Youtube&labelColor=white&color=red)](https://www.youtube.com/@kxle-ph)
[![DISCORD](https://img.shields.io/badge/Elysium_Community-black?logo=discord&logoColor=white&label=Discord&labelColor=%237289da&color=white)](https://discord.gg/vhnRSH7k)
<br>
- Credits to original concept ❤️
