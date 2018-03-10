<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;

class PathEndPoints
{
    private $origin;
    private $destination;

    public static function buildFromBoard(Board $board, Position $origin, Position $destination)
    {
        return new self($board->getBoardTileAtPosition($origin), $board->getBoardTileAtPosition($destination));
    }

    public function __construct(BoardTile $origin, BoardTile $destination)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function getOrigin(): BoardTile
    {
        return $this->origin;
    }

    public function getDestination(): BoardTile
    {
        return $this->destination;
    }

    public function scoreBoardTile(BoardTile $boardTile, ScoredBoardTile $parent = null): ScoredBoardTile
    {
        return new ScoredBoardTile(
            $boardTile,
            new BoardTileScore(
                is_null($parent) ? 0 : $parent->getMoveCost() + 1,
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
