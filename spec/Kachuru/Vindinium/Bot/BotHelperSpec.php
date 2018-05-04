<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BotHelper;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\MineTile;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BotHelperSpec extends ObjectBehavior
{
    /**
     * @var Heroes
     */
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

    public function it_finds_mines_not_owned_by_player()
    {
        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        $this->getMinesNotOwnedByPlayerHero($board)->shouldBeLike(
            [
                new BoardTile(new Position(4, 1), new MineTile()),
                new BoardTile(new Position(0, 3), new MineTile()),
                new BoardTile(new Position(4, 3), new MineTile(), $this->heroes->getHero(2)),
            ]
        );
    }

    private function getBoardWithMines()
    {
        return "          "
            . "$1      $-"
            . "          "
            . "$-      $2"
            . "          ";
    }


    private function buildBoard(string $boardString, int $size)
    {
        return new Board(new TileFactory($this->heroes), $size, $boardString);
    }
}
