<?php

namespace Kachuru\Vindinium\Game\Tile;

class EmptyTile implements TileType
{
    public const TILE_SPECIFICATION = '/  /';
    public const WALKABLE = true;
    public const BASE_MOVE_COST = 1;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
