<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class Game
{
    private $state;
    private $id;
    private $board;
    private $hero;
    private $heroes;

    public static function buildFromVindiniumResponse($state)
    {
        $heroes = Heroes::buildFromVindiniumResponse($state['game']['heroes'], $state['hero']['id']);

        return new self(
            (string) $state['game']['id'],
            new State(
                (int) $state['game']['turn'],
                (int) $state['game']['maxTurns'],
                (bool) $state['game']['finished']
            ),
            Board::buildFromVindiniumResponse(new TileFactory($heroes), $state['game']['board']),
            PlayerHero::buildFromVindiniumResponse($state['hero']),
            $heroes
        );
    }

    private function __construct(string $id, State $state, Board $board, PlayerHero $hero, Heroes $heroes)
    {
        $this->state = $state;
        $this->id = $id;
        $this->board = $board;
        $this->hero = $hero;
        $this->heroes = $heroes;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getHero(): PlayerHero
    {
        return $this->hero;
    }

    public function getHeroes(): Heroes
    {
        return $this->heroes;
    }

    public function isFinished(): bool
    {
        return $this->state->isFinished();
    }
}
