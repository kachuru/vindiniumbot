<?php

namespace Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\Player\Player;
use Kachuru\Vindinium\Game\Player\PlayerPosition;
use Kachuru\Vindinium\Game\Tile\TileFactory;

class Game
{
    /**
     * @var State
     */
    private $state;
    /**
     * @var string
     */
    private $id;
    /**
     * @var Board
     */
    private $board;
    /**
     * @var Player
     */
    private $player;

    public static function buildFromResponse($state, TileFactory $tileFactory)
    {
        return new self(
            (string) $state['game']['id'],
            new State(
                (int) $state['game']['turn'],
                (int) $state['game']['maxTurns'],
                (bool) $state['game']['finished']
            ),
            Board::buildFromVindiniumResponse($tileFactory, $state['game']['board']),
            new Player(
                $state['hero']['id'],
                $state['hero']['life'],
                $state['hero']['gold'],
                $state['hero']['mineCount'],
            // X and Y are the other way round from the board
                new Position(
                    $state['hero']['pos']['y'],
                    $state['hero']['pos']['x']
                )
            )
        );
    }

    private function __construct(string $id, State $state, Board $board, Player $player)
    {
        $this->state = $state;
        $this->id = $id;
        $this->board = $board;
        $this->player = $player;
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

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function isFinished(): bool
    {
        return $this->state->isFinished();
    }
}
