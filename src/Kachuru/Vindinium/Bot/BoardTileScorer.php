<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\BoardTile;

class BoardTileScorer
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
        $moveCost = 1;
        if (!is_null($parent)) {
            $moveCost += $parent->getMoveCost();
        }

        return new ScoredBoardTile(
            $boardTile,
            new BoardTileScore($moveCost, $this->estimateCostToDestination($boardTile)),
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
