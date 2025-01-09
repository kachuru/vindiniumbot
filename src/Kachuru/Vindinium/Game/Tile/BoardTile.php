<?php

namespace Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Hero\Hero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\Type\TileType;

interface BoardTile
{
    public function getPosition(): Position;
    public function getTileType(): TileType;
    public function getTypeName(): string;
    public function getHero(): ?Hero;
    public function isWalkable(): bool;
    public function getBaseMoveCost(): ?int;
}
