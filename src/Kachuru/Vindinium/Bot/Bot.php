<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Player\Player;

interface Bot
{
    public function __construct(Player $player);
    public function move(Board $board): string;
}
