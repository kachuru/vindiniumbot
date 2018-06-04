<?php

namespace Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\Hero;
use Kachuru\Vindinium\Game\Position;

class AuraBoardTile implements BoardTile
{
    /**
     * @var Board
     */
    private $board;
    /**
     * @var BaseBoardTile
     */
    private $baseBoardTile;

    public function __construct(Board $board, BaseBoardTile $baseBoardTile)
    {
        $this->board = $board;
        $this->baseBoardTile = $baseBoardTile;
    }

    public function getPosition(): Position
    {
        return $this->baseBoardTile->getPosition();
    }

    public function getTileType(): TileType
    {
        return $this->baseBoardTile->getTileType();
    }

    public function getTypeName(): string
    {
        return $this->baseBoardTile->getTypeName();
    }

    public function getHero(): ?Hero
    {
        return $this->baseBoardTile->getHero();
    }

    public function isWalkable(): bool
    {
        return $this->baseBoardTile->isWalkable();
    }

    public function getBaseMoveCost(): ?int
    {
        $cost = $this->baseBoardTile->getBaseMoveCost();
        foreach ($this->board->getAdjacentBoardTiles($this->getPosition()) as $boardTile) {
            /**
             * @var BaseBoardTile $boardTile
             */
            $cost += ($boardTile->getTileType())::AURA_MOVE_COST;
        }
        return $cost;
    }
}
