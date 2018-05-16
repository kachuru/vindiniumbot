<?php

namespace Kachuru\Vindinium\Game\Hero;

use Kachuru\Vindinium\Game\Position;

class BaseHero implements Hero
{
    private $id;
    private $name;
    private $life;
    private $gold;
    private $mineCount;
    private $position;

    public static function buildFromVindiniumResponse(array $response)
    {
        return new self(
            $response['id'],
            $response['name'],
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

    public function __construct($id, $name, $life, $gold, $mineCount, Position $position)
    {
        $this->id = $id;
        $this->name = $name;
        $this->life = $life;
        $this->gold = $gold;
        $this->mineCount = $mineCount;
        $this->position = $position;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLife(): int
    {
        return $this->life;
    }

    public function getGold(): int
    {
        return $this->gold;
    }

    public function getMineCount(): int
    {
        return $this->mineCount;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }
}
