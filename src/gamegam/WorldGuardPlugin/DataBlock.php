<?php

namespace gamegam\WorldGuardPlugin;

use pocketmine\utils\SingletonTrait;

class DataBlock
{

	use SingletonTrait;

	public $api;

	public function __construct()
	{
		self::setInstance($this);
		$this->api = Main::getInstance();
	}

	public function getBlock(string $name): array
	{
		return $this->api->block[$name] ?? [];
	}

	public function isBlock(string $name, $block_name): bool
	{
		$bool = false;
		foreach ($this->getBlock($name) as $name) {
			if ($block_name == $name) {
				$bool = true;
				break;
			}
		}
		return $bool;
	}

	// setData
	public function setData_Block(string $name, $block_name): bool
	{
		$bool = false;
		if (!isset($this->api->block[$name][$block_name])) {
			$this->api->block[$name][$block_name] = $block_name;
		} else {
			$bool = true;
		}
		return $bool;
	}

	// remove Data
	public function Remove_Data(string $name, $block_name): bool
	{
		$bool = true;
		if (isset($this->api->block[$name][$block_name])) {
			unset($this->api->block[$name][$block_name]);
		} else {
			$bool = false;
		}
		return $bool;
	}
}