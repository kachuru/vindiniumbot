<?php

namespace Kachuru\Vindinium\Game;

class State
{
    private $turn;
    private $maxTurns;
    private $finished;
    /**
     * @var string
     */
    private $viewUrl;

    public function __construct(int $turn, int $maxTurns, bool $finished, string $viewUrl)
    {
        $this->turn = $turn;
        $this->maxTurns = $maxTurns;
        $this->finished = $finished;
        $this->viewUrl = $viewUrl;
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

    public function getViewUrl(): string
    {
        return $this->viewUrl;
    }
}
