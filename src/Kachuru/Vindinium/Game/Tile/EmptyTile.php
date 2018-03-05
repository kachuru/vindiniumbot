<?php

namespace Kachuru\Vindinium\Game\Tile;

class EmptyTile implements TileType
{
    const TILE_SPECIFICATION = '/  /';

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }
}
