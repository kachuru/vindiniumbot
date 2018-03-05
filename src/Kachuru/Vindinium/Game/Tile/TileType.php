<?php

namespace Kachuru\Vindinium\Game\Tile;

interface TileType
{
    /*
     * Return the pattern that the tile should match
     */
    public static function getPattern(): string;
}
