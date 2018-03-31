<?php

namespace Kachuru\Vindinium\Game\Tile;

class WallTile implements TileType
{
    public const TILE_SPECIFICATION = '/##/';
    public const OUTPUT = '##';
    public const WALKABLE = false;
    public const BASE_MOVE_COST = null;
}
