<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\BaseBoardTile;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Kachuru\Vindinium\SpecHelper;

class AuraBoardTileSpec extends ObjectBehavior
{
    private $heroes;
    private $specHelper;

    public function __construct()
    {
        $this->specHelper = new SpecHelper();
    }

    public function let()
    {
        $this->heroes = $this->specHelper->getHeroes();
    }

    function it_returns_an_aura_next_to_enemy()
    {
        $board = $this->specHelper->buildPlayersBoard($this->heroes);

        $this->beConstructedWith(
            $board,
            new BaseBoardTile(new Position(4, 1), new EmptyTile())
        );

        $this->getBaseMoveCost()->shouldReturn(25);
    }

    function it_returns_an_aura_in_empty_space()
    {
        $board = $this->specHelper->buildPlayersBoard($this->heroes);

        $this->beConstructedWith(
            $board,
            new BaseBoardTile(new Position(3, 3), new EmptyTile())
        );

        $this->getBaseMoveCost()->shouldReturn(5);
    }
}
