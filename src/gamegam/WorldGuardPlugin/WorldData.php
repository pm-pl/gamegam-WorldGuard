<?php

namespace gamegam\WorldGuardPlugin;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;

class WorldData{

	use SingletonTrait;

	public $api;
	public WorldGuard $worldguard;

	public function __construct(){
		self::setInstance($this);
		$this->api = Main::getInstance();
		$this->worldguard = WorldGuard::getInstance();
	}

	public function isName(string $name):bool{
		return isset($this->api->db["name"][$name]);
	}

	public function isInside(Position $position, $minX, $minY, $minZ, $maxX, $maxY, $maxZ, $world) : bool
	{
		$posX = $position->getX();
		$posY = $position->getY();
		$posZ = $position->getZ();
		$realMinX = $minX < $maxX ? $minX : $maxX;
		$realMaxX = $minX > $maxX ? $minX : $maxX;
		if ($posX < $realMinX || $posX > $realMaxX) return false;
		$realMinY = $minY < $maxY ? $minY : $maxY;
		$realMaxY = $minY > $maxY ? $minY : $maxY;

		if ($posY < $realMinY || $posY > $realMaxY) return false;

		$realMinZ = $minZ < $maxZ ? $minZ : $maxZ;
		$realMaxZ = $minZ > $maxZ ? $minZ : $maxZ;

		return $position->getWorld()->getFolderName() == $world && $posZ >= $realMinZ && $posZ <= $realMaxZ;
	}

	public function WorldData($name):array{
		if ($this->isName($name)){
			return $this->api->db["name"][$name];
		}else{
			return [];
		}
	}

	public function getName(Position $position) {
		$name = "(none)";
		$world = $position->getWorld()->getFolderName();
		$x = (int)$position->getX();
		$y = (int)$position->getY();
		$z = (int)$position->getZ();

		$gx = $x >> 7;
		$gz = $z >> 7;

		if (isset($this->api->grid[$world][$gx][$gz])) {
			foreach ($this->api->grid[$world][$gx][$gz] as $zoneName) {
				$d = $this->api->zoneCache[$zoneName] ?? null;
				if ($d === null){
					unset($this->api->zoneCache[$zoneName]);
					continue;
				}
				if ($x >= $d['minX'] && $x <= $d['maxX'] &&
					$y >= $d['minY'] && $y <= $d['maxY'] &&
					$z >= $d['minZ'] && $z <= $d['maxZ']) {

					$name = $zoneName;
					break;
				}
			}
		}

		return $name;
	}

	public function removeGuard(string $name){
		if ($this->isName($name)){
			$world = $this->api->db["name"][$name]["world"] ?? null;

			if ($world !== null && isset($this->zoneCache[$name])) {
				$d = $this->api->zoneCache[$name];
				$minGX = $d['minX'] >> 7;
				$maxGX = $d['maxX'] >> 7;
				$minGZ = $d['minZ'] >> 7;
				$maxGZ = $d['maxZ'] >> 7;

				for ($gx = $minGX; $gx <= $maxGX; $gx++) {
					for ($gz = $minGZ; $gz <= $maxGZ; $gz++) {
						if (isset($this->api->grid[$world][$gx][$gz])) {
							$index = array_search($name, $this->api->grid[$world][$gx][$gz]);
							if ($index !== false) {
								unset($this->api->grid[$world][$gx][$gz][$index]);
							}
						}
					}
				}
			}

			unset($this->api->zoneCache[$name]);
			unset($this->api->block[$name], $this->api->db["name"][$name]);
			$this->api->save();
		}
	}

	public function getBlockJoin(Position $position): bool {
		$world = $position->getWorld()->getFolderName();
		$x = (int)$position->getX();
		$y = (int)$position->getY();
		$z = (int)$position->getZ();

		$gx = $x >> 7;
		$gz = $z >> 7;

		if (isset($this->api->grid[$world][$gx][$gz])) {
			foreach ($this->api->grid[$world][$gx][$gz] as $zoneName) {
				$d = $this->api->zoneCache[$zoneName] ?? null;
				if ($d === null){
					unset($this->api->zoneCache[$zoneName]);
					continue;
				}
				if ($this->isInside($position, $d['minX'], $d['minY'], $d['minZ'], $d['maxX'], $d['maxY'], $d['maxZ'], $zoneName)){
					return true;
				}
			}
		}

		return false;
	}

	public function addMember(string $name, $pp){
		if ($this->isName($name)){
			$this->api->db["name"][$name]["member"][$pp] = $pp;
		}
		$this->api->save();
	}

	public function RemoveMember(string $name, $pp){
		if ($this->isName($name)){
			unset($this->api->db["name"][$name]["member"][$pp]);
		}
		$this->api->save();
	}

	public function WorldflagData($name, string $type, string $allow = "deny"): bool
	{
		$a = new Type();
		$bool = false;
		if ($this->isName($name) && $a->isType($type)) {
			if ($allow == "deny") {
				$this->api->db["name"][$name]["flag"][$type] = true;
			} else {
				unset($this->api->db["name"][$name]["flag"][$type]);
			}
		}
		$this->api->save();
		return $bool;
	}

	public function CreateGuard(Player $p, $name) {
		if ($this->worldguard->isModel($p)) {
			if (!$this->isName($name)) {
				$data = $this->worldguard->getPlayerData($p)["last"] ?? null;
				if ($data == null) return;

				$pos1 = $data["pos1"] ?? null;
				$pos2 = $data["pos2"] ?? null;
				if ($pos1 == null || $pos2 == null) return;

				$ex1 = explode(":", $pos1);
				$ex2 = explode(":", $pos2);
				$world = $ex1[3] ?? null;
				if ($world == null) return;

				$this->api->db["name"][$name] = [
					"member" => [],
					"flag" => [],
					"pos1" => $pos1,
					"pos2" => $pos2,
					"world" => $world
				];
				$this->api->save();

				$minX = (int)min($ex1[0], $ex2[0]);
				$maxX = (int)max($ex1[0], $ex2[0]);
				$minY = (int)min($ex1[1], $ex2[1]);
				$maxY = (int)max($ex1[1], $ex2[1]);
				$minZ = (int)min($ex1[2], $ex2[2]);
				$maxZ = (int)max($ex1[2], $ex2[2]);

				$this->api->zoneCache[$name] = [
					'minX' => $minX, 'maxX' => $maxX,
					'minY' => $minY, 'maxY' => $maxY,
					'minZ' => $minZ, 'maxZ' => $maxZ,
					'world' => $world
				];

				$minGX = $minX >> 7;
				$maxGX = $maxX >> 7;
				$minGZ = $minZ >> 7;
				$maxGZ = $maxZ >> 7;

				for ($gx = $minGX; $gx <= $maxGX; $gx++) {
					for ($gz = $minGZ; $gz <= $maxGZ; $gz++) {
						$this->api->grid[$world][$gx][$gz][] = $name;
					}
				}

				$this->worldguard->cancel($p);
			}
		}
	}
}