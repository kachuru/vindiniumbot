<?php

namespace Kachuru\Vindinium\Game\Tile;

class MineTile implements TileType
{
    public const TILE_SPECIFICATION = '/\$(?P<hero>[1-4\-])/';
    public const WALKABLE = false;
    public const BASE_MOVE_COST = 1;
}
