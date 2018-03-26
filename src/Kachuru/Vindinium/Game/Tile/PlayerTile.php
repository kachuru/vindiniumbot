<?php

namespace Kachuru\Vindinium\Game\Tile;

class PlayerTile implements TileType
{
    public const TILE_SPECIFICATION = '/@(?P<player>[1-4])/';
    public const WALKABLE = true;
    public const BASE_MOVE_COST = 5;
}
