<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\BoardTile;

class PathEndPoints
{
    private $origin;
    private $destination;

    public function __construct(BoardTile $origin, BoardTile $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function scoreBoardTile(BoardTile $boardTile, ScoredBoardTile $parent = null): ScoredBoardTile
    {
        return new ScoredBoardTile(
            $boardTile,
            new BoardTileScore(
                is_null($parent) ? 1 : $parent->getMoveCost() + 1,
                $this->estimateCostToDestination($boardTile)
            ),
            $parent
        );
    }

    public function estimateCostToDestination(BoardTile $from): int
    {
        // LoD breakage
        return abs($from->getPosition()->getX() - $this->destination->getPosition()->getX())
            + abs($from->getPosition()->getY() - $this->destination->getPosition()->getY());
    }
}
