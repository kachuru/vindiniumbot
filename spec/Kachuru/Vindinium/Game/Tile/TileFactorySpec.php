<?php

namespace spec\Kachuru\Vindinium\Game\Tile;

use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\EnemyHeroTile;
use Kachuru\Vindinium\Game\Tile\PlayerHeroTile;
use Kachuru\Vindinium\Game\Tile\TavernTile;
use Kachuru\Vindinium\Game\Tile\TileType;
use Kachuru\Vindinium\Game\Tile\WallTile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TileFactorySpec
 * @mixin \Kachuru\Vindinium\Game\Tile\TileFactory
 * @package spec\Kachuru\Vindinium
 */
class TileFactorySpec extends ObjectBehavior
{
    function it_returns_the_right_type()
    {
        $heroes = new Heroes([
            new PlayerHero(
                new BaseHero(1, 'PlayerOne', 100, 0, 0, new Position(1, 0))
            ),
            new EnemyHero(
                new BaseHero(2, 'PlayerTwo', 100, 0, 0, new Position(1, 0))
            ),
            new EnemyHero(
                new BaseHero(3, 'PlayerThree', 100, 0, 0, new Position(1, 0))
            ),
            new EnemyHero(
                new BaseHero(4, 'PlayerOne', 100, 0, 0, new Position(1, 0))
            ),
        ]);

        $this->beConstructedWith($heroes);

        $this->checkReturnType(new EmptyTile(), '  ');

        $this->checkReturnType(new WallTile(), '##');

        $this->checkReturnType(new MineTile(), '$-');
        $this->checkReturnType(new MineTile(), '$1', $heroes->getHero(1));
        $this->checkReturnType(new MineTile(), '$2', $heroes->getHero(2));
        $this->checkReturnType(new MineTile(), '$3', $heroes->getHero(3));
        $this->checkReturnType(new MineTile(), '$4', $heroes->getHero(4));

        $this->checkReturnType(new PlayerHeroTile(), '@1', $heroes->getHero(1));
        $this->checkReturnType(new EnemyHeroTile(), '@2', $heroes->getHero(2));
        $this->checkReturnType(new EnemyHeroTile(), '@3', $heroes->getHero(3));
        $this->checkReturnType(new EnemyHeroTile(), '@4', $heroes->getHero(4));

        $this->checkReturnType(new TavernTile(), '[]');
    }

    private function checkReturnType(TileType $type, $content, $player = null)
    {
        $position = new Position(1, 1);
        $this->buildTile($content, $position)->shouldBeLike(
            new BoardTile($position, $type, $player)
        );
    }
}
