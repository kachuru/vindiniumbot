<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\BoardTile;

class ScoredBoardTile
{
    /**
     * @var BoardTile
     */
    private $boardTile;
    /**
     * @var BoardTileScore
     */
    private $boardTileScore;
    /**
     * @var ScoredBoardTile
     */
    private $parent;

    public function __construct(BoardTile $boardTile, BoardTileScore $boardTileScore, ScoredBoardTile $parent = null)
    {
        $this->boardTile = $boardTile;
        $this->boardTileScore = $boardTileScore;
        $this->parent = $parent;
    }

    public function getParent(): ScoredBoardTile
    {
        return $this->parent;
    }

    public function getBoardTile(): BoardTile
    {
        return $this->boardTile;
    }

    public function getPosition(): Position
    {
        return $this->boardTile->getPosition();
    }

    public function getMoveCost()
    {
        return $this->boardTileScore->getMoveCost();
    }

    public function getScore(): int
    {
        return $this->boardTileScore->getScore();
    }

    public function isAtDestination(): bool
    {
        return (bool) $this->boardTileScore->getDistanceToDestination() == 0;
    }

    public function hasParent()
    {
        return !is_null($this->parent);
    }
}
