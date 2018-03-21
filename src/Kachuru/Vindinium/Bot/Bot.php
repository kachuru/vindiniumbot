<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;

interface Bot
{
    public function chooseNextMove(Board $board, Player $player): string;
}
