<?php

namespace spec\Kachuru\Vindinium\Game\Hero;

use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Position;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HeroesSpec extends ObjectBehavior
{
    public function it_creates_a_heroes_collection()
    {
        $this->beConstructedWith([
            new BaseHero(1, 'PlayerOne', 100, 0, 0, new Position(1, 0)),
            new BaseHero(2, 'PlayerTwo', 100, 0, 0, new Position(1, 0)),
            new BaseHero(3, 'PlayerThree', 100, 0, 0, new Position(1, 0)),
            new BaseHero(4, 'PlayerOne', 100, 0, 0, new Position(1, 0)),
        ]);
    }

    public function it_gets_hero_with_id()
    {
        $heroOne = new BaseHero(1, 'PlayerOne', 100, 0, 0, new Position(1, 0));

        $this->beConstructedWith([
            $heroOne,
            new BaseHero(2, 'PlayerTwo', 100, 0, 0, new Position(1, 0)),
            new BaseHero(3, 'PlayerThree', 100, 0, 0, new Position(1, 0)),
            new BaseHero(4, 'PlayerOne', 100, 0, 0, new Position(1, 0)),
        ]);

        $this->getHero(1)->shouldReturn($heroOne);
    }
}
