<?php

namespace gamegam\WorldGuardPlugin;

use pocketmine\Server;

class Type{

	private array $default = [
		"build" => "build",
		"tnt" => "tnt",
		"invincible" => "invincible",
		"lava-flow" => "lava-flow",
		"use" => "use",
		"water" => "water",
		"tnt-damage" => "tnt-damage",
		"pvp" => "pvp",
		"mob-damage" => "mob-damage",
		"mob-pvp" => "mob-pvp",
		"fire" => "fire",
		"mob-spawn" => "mob-spawn",
		"chat" => "chat",
        "exit" => "exit",
		"item-drop" => "item-drop",
		"tp" => "tp",
		"fall-damage" => "fall-damage",
		"falling-blocks" => "falling-blocks",
        "keep-inventory" => "keep-inventory",
		"fly" => "fly",
		"bow" => "bow",
		"ender_pearl" => "ender_pearl",
		"entry" => "entry"
	];

	private array $flag = [];

	private bool $loag = false;

	public function __construct() {
		$this->flag = $this->default;
	}

	public function addFlag(string $flag = "build")
	{
		$flag = strtolower($flag);
		if (count($this->flag) >= 10000) {
			throw new \RuntimeException("You can add up to 10000 in terms of performance.");

		}
		if (!isset($this->flag[$flag]) && !isset($this->default[$flag])) {
			$this->flag[$flag] = $flag;
			Server::getInstance()->getLogger()->info("§a{$flag} has been added.");
			$this->loag = false;
		}else{
			throw new \RuntimeException("{$flag} has already been added.");
		}
	}

	public function remove_name(): string
	{
		return $this->remove_name;
	}

	public function deleteFlag(string $flag = ""): bool
	{
		$removed = true;
		if ($flag !== ""){
			if (! isset($this->default[$flag])){
				if (isset($this->flag[$flag])){
					unset($this->flag[$flag]);
				}else{
					$removed = false;
				}
			}
		}
		return $removed;
	}

	public function getArray(): array
	{
		return $this->flag;
	}

	public function isType($name): bool{
		return isset($this->flag[$name]);
	}
}