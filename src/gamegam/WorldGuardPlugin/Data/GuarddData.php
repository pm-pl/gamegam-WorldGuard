<?php

namespace gamegam\WorldGuardPlugin\Data;


use gamegam\WorldGuardPlugin\WorldData;
use gamegam\WorldGuardPlugin\WorldGuard;
use pocketmine\block\Block;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class GuarddData
{

	use SingletonTrait;

	public $api, $worldguard, $data;

	public function __construct()
	{
		self::setInstance($this);
		$this->api = Server::getInstance()->getPluginManager()->getPlugin("WorldGuardPlugin");
		$this->worldguard = WorldGuard::getInstance();
		$this->data = WorldData::getInstance();
	}

	public function getFlag(string $name, $flag = "build"): bool
	{
		return $this->data->isName($name) && isset($this->api->db["name"][$name]["flag"][$flag]);
	}

	public function getChat(string $name): bool
	{
		return $this->data->isName($name) && ($this->api->db["name"][$name]["flag"]["chat"] ?? false);
	}

	public function getMobSpawn(string $name): bool
	{
		return $this->data->isName($name) && ($this->api->db["name"][$name]["flag"]["mob-spawn"] ?? false);
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
		return $this->getFlag($name, "interact");
	}

	public function getMembers(string $name, $p): bool
	{
		return $this->data->isName($name) && ($this->api->db["name"][$name]["member"][strtolower($p)] ?? false);
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

	public function getWater(string $name): bool
	{
		return $this->getFlag($name, "water");
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

	// exit
	public function getExit(string $name): bool
	{
		return $this->getFlag($name, "exit");
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

	// bow
	public function getBow(string $name): bool
	{
		return $this->getFlag($name, "bow");
	}

	public function getPearl(string $name): bool
	{
		return $this->getFlag($name, "ender_pearl");
	}

	public function getEntry(string $name): bool
	{
		return $this->getFlag($name, "entry");
	}
}