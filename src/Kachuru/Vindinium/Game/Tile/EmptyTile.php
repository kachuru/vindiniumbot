<?php

namespace Kachuru\Vindinium\Game\Tile;

class EmptyTile implements TileType
{
    const TILE_SPECIFICATION = '/  /';

    const WALKABLE = true;

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }

    public function isWalkable(): bool
    {
        return self::WALKABLE;
    }
}
