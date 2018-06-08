<?php

namespace Kachuru\Vindinium\Game\Tile\Type;

class EmptyTile implements TileType
{
    public const TILE_SPECIFICATION = '/  /';
    public const WALKABLE = true;
    public const BASE_MOVE_COST = 5;
    public const AURA_MOVE_COST = 0;
}
