<?php

namespace Kachuru\Vindinium\Game;

class State
{
    private $turn;
    private $maxTurns;
    private $finished;

    public function __construct(int $turn, int $maxTurns, bool $finished)
    {
        $this->turn = $turn;
        $this->maxTurns = $maxTurns;
        $this->finished = $finished;
    }

    public function getTurn(): int
    {
        return $this->turn;
    }

    public function getMaxTurns(): int
    {
        return $this->maxTurns;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }
}
