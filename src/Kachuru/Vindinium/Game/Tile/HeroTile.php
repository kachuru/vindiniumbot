<?php

namespace Kachuru\Vindinium\Game\Tile;

class HeroTile implements TileType
{
    public const TILE_SPECIFICATION = '/@(?P<hero>[1-4])/';
    public const OUTPUT = '@%d';
    public const WALKABLE = true;
}
