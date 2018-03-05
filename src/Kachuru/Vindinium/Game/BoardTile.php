<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Tile\Tile;

class BoardTile
{
    private $tile;
    private $position;

    public function __construct(Tile $tile, Position $position)
    {
        $this->tile = $tile;
        $this->position = $position;
    }

    public function getTile(): Tile
    {
        return $this->tile;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

}