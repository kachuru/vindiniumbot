<?php

namespace Kachuru\Vindinium\Game\Tile;

class MineTile implements TileType
{
    public const TILE_SPECIFICATION = '/\$(?P<player>[1-4\-])/';

    const WALKABLE = false;
    public const BASE_MOVE_COST = -1;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
