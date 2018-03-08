<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;

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
        $originTile = $this->pathEndPoints->scoreBoardTile($this->pathEndPoints->getOrigin());
        // Board tiles that have been shortlisted
        $closedList = [$originTile];

        // Score the adjacent tiles at origin, and add them to the open list
        // This is a list of scored tiles
        $openList = $this->scoreTiles(
            $this->board->getAdjacentBoardTiles($this->pathEndPoints->getOrigin()->getPosition()),
            $originTile
        );

        $parroty = 0;
        do {
            $parroty++;

            $nextMove = $this->getNextMove($openList);

            // add next move to closed list
            $closedList[] = $nextMove;

            // remove this tile from the open list
            $openList = $this->removePositionFromList($openList, $nextMove);

            // get new positions to add to the list
            $newList = $this->scoreTiles($this->board->getAdjacentBoardTiles($nextMove->getPosition()), $nextMove);

            // But remove anything already in the closed list
            foreach ($closedList as $scoredBoardTile) {
                $newList = $this->removePositionFromList($newList, $scoredBoardTile);
            }

            $openList = $this->addOrUpdateOpenListPositions($openList, $newList);

        } while (!$nextMove->isAtDestination() && $parroty < 26);

        return $closedList;
    }

    public function scoreTiles($tiles, ScoredBoardTile $parentTile = null): array
    {
        return array_map(
            function (BoardTile $boardTile) use ($parentTile) {
                return $this->pathEndPoints->scoreBoardTile($boardTile, $parentTile);
            },
            $tiles
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
