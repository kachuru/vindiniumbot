<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Position;

interface Bot
{
    public function __construct(Player $player);
    public function move(Board $board, Position $position): string;
}
