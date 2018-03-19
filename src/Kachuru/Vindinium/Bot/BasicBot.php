<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Game;
use Kachuru\Vindinium\Game\Player\Player;

class BasicBot implements Bot
{
    private $currentDestination;
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function move(Board $board): string
    {
        $dirs = ['Stay', 'North', 'South', 'East', 'West'];
        return $dirs[mt_rand(0, count($dirs) - 1)];
    }

    public function getPathToNearestAvailableMine(Board $board)
    {
        return (new PathFinder(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($this->player->getPosition()),
                $this->getMinesNotOwnedByMe($board)
            )
        ))->find();
    }

    public function getMinesNotOwnedByMe(Board $board)
    {
        return array_values(array_filter(
            $board->getMineTiles(),
            function (BoardTile $boardTile) {
                return $boardTile->getPlayer() != $this->player->getId();
            }
        ));
    }
}
