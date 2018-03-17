<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;

class PathEndPoints
{
    private $origin;
    private $destinations;

    public static function buildFromBoard(Board $board, Position $origin, array $destinations)
    {
        return new self(
            $board->getBoardTileAtPosition($origin),
            array_map(
                function (Position $position) use ($board) {
                    return $board->getBoardTileAtPosition($position);
                },
                $destinations
            )
        );
    }

    public function __construct(BoardTile $origin, array $destinations)
    {
        $this->origin = $origin;
        $this->destinations = $destinations;
    }

    public function getOrigin(): BoardTile
    {
        return $this->origin;
    }

    public function getDestinations(): array
    {
        return $this->destinations;
    }

    public function scoreBoardTile(BoardTile $boardTile, ScoredBoardTile $parent = null): ScoredBoardTile
    {
        return new ScoredBoardTile(
            $boardTile,
            new BoardTileScore(
                is_null($parent) ? 0 : $parent->getMoveCost() + 1,
                $this->estimateCostToOrigin($boardTile)
            ),
            $parent
        );
    }

    public function estimateCostToOrigin(BoardTile $from): int
    {
        // LoD breakage
        return abs($from->getPosition()->getX() - $this->origin->getPosition()->getX())
            + abs($from->getPosition()->getY() - $this->origin->getPosition()->getY());
    }
}
