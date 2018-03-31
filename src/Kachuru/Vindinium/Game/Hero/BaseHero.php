<?php

namespace Kachuru\Vindinium\Game\Hero;

use Kachuru\Vindinium\Game\Position;

class BaseHero implements Hero
{
    const HERO_OUTPUT = '[%d] %s [%2d, %2d] - Life:%3d Gold: %4d Mines: %2d';

    private $id;
    private $name;
    private $life;
    private $gold;
    private $mineCount;
    private $position;

    public static function buildFromVindiniumResponse(array $response): Hero
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

    public function __toString(): string
    {
        return sprintf(
            self::HERO_OUTPUT,
            $this->id,
            str_pad(substr($this->name, 0, 13), 13),
            $this->position->getX(),
            $this->position->getY(),
            $this->life,
            $this->gold,
            $this->mineCount
        );
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
