<?php

namespace Kachuru\Vindinium\Game\Player;

use Kachuru\Vindinium\Game\Position;

class Player
{
    const PLAYER_OUTPUT = 'Player %d [%2d, %2d] - Life: %3d Gold: %3d Mines: %2d';

    private $id;
    private $life;
    private $gold;
    private $mineCount;
    private $position;

    public static function buildFromVindiniumResponse(array $response): Player
    {
        return new self(
            $response['id'],
            $response['life'],
            $response['gold'],
            $response['mineCount'] ,
            // X and Y are the other way round from the board
            new Position(
                $response['pos']['y'],
                $response['pos']['x']
            )
        );
    }

    public static function buildAllFromVindiniumResponse(array $players): array
    {
        foreach ($players as $i => $player) {
            $players[$i] = self::buildFromVindiniumResponse($player);
        }

        return $players;
    }

    public function __construct($id, $life, $gold, $mineCount, Position $position)
    {
        $this->id = $id;
        $this->life = $life;
        $this->gold = $gold;
        $this->mineCount = $mineCount;
        $this->position = $position;
    }

    public function __toString()
    {
        return sprintf(
            self::PLAYER_OUTPUT,
            $this->id,
            $this->position->getX(),
            $this->position->getY(),
            $this->life,
            $this->gold,
            $this->mineCount
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLife()
    {
        return $this->life;
    }

    /**
     * @return mixed
     */
    public function getGold()
    {
        return $this->gold;
    }

    /**
     * @return mixed
     */
    public function getMineCount()
    {
        return $this->mineCount;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }
}
