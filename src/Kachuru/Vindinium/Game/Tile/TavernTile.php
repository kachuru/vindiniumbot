<?php

namespace Kachuru\Vindinium\Game\Tile;

class TavernTile implements TileType
{
    public const TILE_SPECIFICATION = '/\[\]/';
    public const OUTPUT = '[]';
    public const WALKABLE = false;
    public const BASE_MOVE_COST = 1;
}
