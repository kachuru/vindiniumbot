<?php

namespace Kachuru\Vindinium\Game\Tile;

class WallTile implements TileType
{
    const TILE_SPECIFICATION = '/##/';
    const WALKABLE = false;
    const BASE_MOVE_COST = -1;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
