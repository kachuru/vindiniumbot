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
    /**
     * @var array
     */
    private $enemies;

    public static function buildFromVindiniumResponse($state, TileFactory $tileFactory)
    {
        return new self(
            (string) $state['game']['id'],
            new State(
                (int) $state['game']['turn'],
                (int) $state['game']['maxTurns'],
                (bool) $state['game']['finished']
            ),
            Board::buildFromVindiniumResponse($tileFactory, $state['game']['board']),
            Player::buildFromVindiniumResponse($state['hero']),
            Player::buildAllFromVindiniumResponse(
                array_diff_key(
                    $state['game']['heroes'],
                    [$state['hero']['id'] - 1 => null]
                )
            )
        );
    }

    private function __construct(string $id, State $state, Board $board, Player $player, array $enemies)
    {
        $this->state = $state;
        $this->id = $id;
        $this->board = $board;
        $this->player = $player;
        $this->enemies = $enemies;
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

    public function getEnemies(): array
    {
        return $this->enemies;
    }

    public function isFinished(): bool
    {
        return $this->state->isFinished();
    }
}
