<?php

namespace Kachuru\Vindinium\Game\Tile;

class PlayerTile implements TileType
{
    const TILE_SPECIFICATION = '/@(?P<player>[1-4])/';
    const WALKABLE = true;
    const BASE_MOVE_COST = 5;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
