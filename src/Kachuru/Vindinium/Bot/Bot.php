<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Player;

interface Bot
{
    public function chooseNextMove(Board $board, Player $player): string;
}
