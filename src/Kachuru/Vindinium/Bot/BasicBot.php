<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Game;

class BasicBot implements Bot
{
    private $currentDestination;

    public function move(Game $game): string
    {
        if (is_null($this->currentDestination)) {
            $this->chooseDestination($game->getBoard());
        }

        $dirs = ['Stay', 'North', 'South', 'East', 'West'];
        return $dirs[mt_rand(0, count($dirs) - 1)];
    }

    private function chooseDestination(Board $board): BoardTile
    {
        // decide destination
    }
}
