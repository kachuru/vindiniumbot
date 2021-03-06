<?php

namespace Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\PlayerHero;

interface Bot
{
    public function getHandle(): string;
    public function getName(): string;
    public function chooseNextMove(Board $board, PlayerHero $player): string;
    public function getMove(): string;
    public function getDecisionTime(): float;
    public function hasPath(): bool;
    public function getPath(): array;
}
