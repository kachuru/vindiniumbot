<?php

namespace Kachuru\Vindinium\Game\Tile;

class PlayerTile implements TileType
{
    const TILE_SPECIFICATION = '/@(?P<player>[1-4])/';
    const WALKABLE = false;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
