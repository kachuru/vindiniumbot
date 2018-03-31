<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Hero\Hero;
use Kachuru\Vindinium\Game\Tile\TileType;

class BoardTile
{
    private $position;
    private $tileType;
    private $hero;

    public function __construct(Position $position, TileType $tileType, Hero $hero = null)
    {
        $this->position = $position;
        $this->tileType = $tileType;
        $this->hero = $hero;
    }

    public function __toString(): string
    {
        return (string) sprintf(($this->tileType)::OUTPUT, is_null($this->hero) ? '-' : $this->hero->getId());
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getTileType(): TileType
    {
        return $this->tileType;
    }

    public function getTypeName(): string
    {
        $class = get_class($this->tileType);
        return substr($class, strrpos($class, '\\') + 1);
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function isWalkable(): bool
    {
        return ($this->tileType)::WALKABLE;
    }

    public function getBaseMoveCost(): ?int
    {
        return ($this->tileType)::BASE_MOVE_COST;
    }
}
