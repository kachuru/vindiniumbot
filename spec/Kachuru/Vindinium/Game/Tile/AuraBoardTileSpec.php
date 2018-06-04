<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\AuraBoardTile;
use Kachuru\Vindinium\Game\Tile\BaseBoardTile;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuraBoardTileSpec extends ObjectBehavior
{
    private $heroes;

    public function let()
    {
        $this->heroes = new Heroes([
            new PlayerHero(new BaseHero(1, 'HeroOne', 100, 0, 0, new Position(1, 1))),
            new EnemyHero(new BaseHero(2, 'HeroTwo', 100, 0, 0, new Position(4, 0))),
            new EnemyHero(new BaseHero(3, 'HeroThree', 100, 0, 0, new Position(0, 4))),
            new EnemyHero(new BaseHero(4, 'HeroFour', 100, 0, 0, new Position(4, 4))),
        ]);
    }

    function it_returns_an_aura_next_to_enemy()
    {
        $board = $this->buildBoard($this->getBoardWithPlayers(), 5);

        $this->beConstructedWith(
            $board,
            new BaseBoardTile(new Position(4, 1), new EmptyTile())
        );

        $this->getBaseMoveCost()->shouldReturn(25);
    }

    function it_returns_an_aura_in_empty_space()
    {
        $board = $this->buildBoard($this->getBoardWithPlayers(), 5);

        $this->beConstructedWith(
            $board,
            new BaseBoardTile(new Position(3, 3), new EmptyTile())
        );

        $this->getBaseMoveCost()->shouldReturn(5);
    }

    private function getBoardWithPlayers()
    {
        return "        @2"
            . "  @1      "
            . "          "
            . "          "
            . "@3      @4";
    }

    private function buildBoard(string $boardString, int $size)
    {
        return new Board(new TileFactory($this->heroes), $size, $boardString);
    }
}
