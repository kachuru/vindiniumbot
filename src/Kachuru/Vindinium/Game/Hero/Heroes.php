<?php

namespace Kachuru\Vindinium\Game\Hero;

use Kachuru\Util\Collection;

class Heroes extends Collection
{
    public const SET_TYPE = 'Kachuru\Vindinium\Game\Hero\Hero';

    public static function buildFromVindiniumResponse($heroes, $playerHeroId)
    {
        foreach ($heroes as $i => $hero) {
            $heroes[$i] = $hero['id'] == $playerHeroId
                ? PlayerHero::buildFromVindiniumResponse($hero)
                : EnemyHero::buildFromVindiniumResponse($hero);
        }

        return new self($heroes);
    }

    public function getHero($heroId)
    {
        return $this->findBy('getId', $heroId);
    }
}
