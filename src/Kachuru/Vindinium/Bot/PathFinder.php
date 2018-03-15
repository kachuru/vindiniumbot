<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;

class PathFinder
{
    /**
     * @var Board
     */
    private $board;
    /**
     * @var PathEndPoints
     */
    private $pathEndPoints;

    public function __construct(Board $board, PathEndPoints $pathEndPoints)
    {
        $this->board = $board;
        $this->pathEndPoints = $pathEndPoints;
    }

    public function find(): array
    {
        /**
         * Multi-destination - start at the destinations, get adjacent tiles for each destination, then work out
         * which is the lowest score for all those tiles.
         */

        $originTile = $this->pathEndPoints->scoreBoardTile($this->pathEndPoints->getOrigin());
        // Board tiles that have been shortlisted
        $closedList = [$originTile];

        // Score the adjacent tiles at origin, and add them to the open list
        // This is a list of scored tiles
        $openList = $this->scoreTiles(
            $this->board->getAdjacentBoardTiles($this->pathEndPoints->getOrigin()->getPosition()),
            $originTile
        );

        do {
            $nextMove = $this->getNextMove($openList);

            $closedList[] = $nextMove;

            $openList = $this->removePositionFromList($openList, $nextMove);

            $newList = $this->scoreTiles($this->board->getAdjacentBoardTiles($nextMove->getPosition()), $nextMove);

            foreach ($closedList as $scoredBoardTile) {
                $newList = $this->removePositionFromList($newList, $scoredBoardTile);
            }

            $openList = $this->addOrUpdateOpenListPositions($openList, $newList);

        } while (!$nextMove->isAtDestination());

        return $this->traceParents($nextMove);
    }

    public function scoreTiles($tiles, ScoredBoardTile $parentTile = null): array
    {
        // Need to check if the tile is walkable or the destination
        return array_reduce(
            $tiles,
            function ($boardTiles, BoardTile $boardTile) use ($parentTile) {
                if ($boardTile->isWalkable() || $this->isDestination($boardTile)) {
                    $boardTiles[] = $this->pathEndPoints->scoreBoardTile($boardTile, $parentTile);
                }
                return $boardTiles;
            }

        );
    }

    public function getNextMove(array $checkTiles): ScoredBoardTile
    {
        return array_reduce(
            $checkTiles,
            function (ScoredBoardTile $bestTile = null, ScoredBoardTile $checkTile) {
                if (is_null($bestTile) || $checkTile->getScore() <= $bestTile->getScore()) {
                    $bestTile = $checkTile;
                }
                return $bestTile;
            }
        );
    }

    private function traceParents(ScoredBoardTile $destination)
    {
        $parent = $destination->getParent();

        $path = [$destination, $parent];

        while ($parent->hasParent()) {
            $parent = $parent->getParent();
            if (!is_null($parent)) {
                $path[] = $parent;
            }
        }

        return array_reverse($path);
    }

    private function isDestination(BoardTile $boardTile)
    {
        return $this->pathEndPoints->getDestination() == $boardTile;
    }

    private function removePositionFromList($tileList, ScoredBoardTile $removeTile)
    {
        return array_filter(
            $tileList,
            function (ScoredBoardTile $checkTile) use ($removeTile) {
                return ($checkTile->getPosition() != $removeTile->getPosition());
            }
        );
    }

    private function addOrUpdateOpenListPositions($openList, $newList)
    {
        return array_merge($openList, $newList);
    }
}
