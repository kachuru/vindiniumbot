<?php

namespace Kachuru\Vindinium\Game\Tile;

class MineTile implements TileType
{
    public const TILE_SPECIFICATION = '/\$[1-4\-]/';

    public static function getPattern(): string
    {
        return self::TILE_SPECIFICATION;
    }
}
