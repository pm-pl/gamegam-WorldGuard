<?php

namespace gamegam\WorldGuardPlugin\Data;


use gamegam\WorldGuardPlugin\WorldData;
use gamegam\WorldGuardPlugin\WorldGuard;
use pocketmine\block\Block;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class GuarddFullData
{

	use SingletonTrait;

	public $api, $worldguard, $data;

	public function __construct()
	{
		self::setInstance($this);
		$this->api = Server::getInstance()->getPluginManager()->getPlugin("WorldGuardPlugin");
		$this->worldguard = WorldGuard::getInstance();
	}

	public function getFlag(string $name, $flag = "build"): bool
	{
		return $this->isName($name) && isset($this->api->worlds["name"][$name]["flag"][$flag]);
	}

	public function isName(string $name): bool
	{
		return isset($this->api->worlds["name"][$name]);
	}

	public function getChat(string $name): bool
	{
		return $this->getFlag($name, "chat");
	}

	public function getMobSpawn(string $name): bool
	{
		return $this->getFlag($name, "mob-spawn");
	}

	/**
	 * If you detect a build in the area
	 */
	public function getBuild(string $name): bool
	{
		return $this->getFlag($name, "build");
	}

	public function getInteract(string $name): bool
	{
		return $this->getFlag($name, "use");
	}

	public function getMembers(string $name, $p): bool
	{
		return $this->isName($name) && isset($this->api->worlds["name"][$name]["member"][strtolower($p)]);
	}

	public function getTNT(string $name): bool
	{
		return $this->getFlag($name, "tnt");
	}

	public function getinvincible(string $name): bool
	{
		return $this->getFlag($name, "invincible");
	}

	public function getLave(string $name): bool
	{
		return $this->getFlag($name, "lava-flow");
	}

	public function getTNTDamage(string $name): bool
	{
		return $this->getFlag($name, "tnt-damage");
	}

	public function getPVP(string $name): bool
	{
		return $this->getFlag($name, "pvp");
	}

	public function getMobDamage(string $name): bool
	{
		return $this->getFlag($name, "mob-damage");
	}

	public function getMobPVP(string $name): bool
	{
		return $this->getFlag($name, "mob-pvp");
	}

	public function getfire(string $name): bool
	{
		return $this->getFlag($name, "fire");
	}

	// item drop
	public function getItemDrop(string $name): bool
	{
		return $this->getFlag($name, "item-drop");
	}

	// tp
	public function getTP(string $name): bool
	{
		return $this->getFlag($name, "tp");
	}

	// insave
	public function getDeath(string $name): bool
	{
		return $this->getFlag($name, "keep-inventory");
	}

	public function getFly(string $name): bool
	{
		return $this->getFlag($name, "fly");
	}

	public function getBow(string $name): bool
	{
		return $this->getFlag($name, "bow");
	}

	// end
	public function getPearl(string $name): bool
	{
		return $this->getFlag($name, "ender_pearl");
	}
}