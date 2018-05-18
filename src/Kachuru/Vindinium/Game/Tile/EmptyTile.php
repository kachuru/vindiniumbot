<?php

namespace Kachuru\Vindinium\Game\Tile;

class EmptyTile implements TileType
{
    public const TILE_SPECIFICATION = '/  /';
    public const WALKABLE = true;
    public const BASE_MOVE_COST = 1;
}
