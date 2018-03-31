<?php

namespace Kachuru\Vindinium\Game\Hero;

use Kachuru\Vindinium\Game\Position;

class PlayerHero implements Hero
{
    private $hero;

    public static function buildFromVindiniumResponse(array $response): Hero
    {
        return new self(BaseHero::buildFromVindiniumResponse($response));
    }

    public function __construct(BaseHero $hero)
    {
        $this->hero = $hero;
    }

    public function __toString(): string
    {
        return (string) $this->hero;
    }

    public function getId(): int
    {
        return (int) $this->hero->getId();
    }

    public function getName(): string
    {
        return (string) $this->hero->getName();
    }

    public function getLife(): int
    {
        return (int) $this->hero->getLife();
    }

    public function getGold(): int
    {
        return (int) $this->hero->getGold();
    }

    public function getMineCount(): int
    {
        return (int) $this->hero->getMineCount();
    }

    public function getPosition(): Position
    {
        return $this->hero->getPosition();
    }
}
