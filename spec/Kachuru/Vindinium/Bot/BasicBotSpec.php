<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BasicBot;
use Kachuru\Vindinium\Bot\BotHelper;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\Hero\BaseHero;
use Kachuru\Vindinium\Game\Hero\EnemyHero;
use Kachuru\Vindinium\Game\Hero\Heroes;
use Kachuru\Vindinium\Game\Hero\PlayerHero;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use PhpSpec\ObjectBehavior;

/**
 * Class BasicBotSpec
 * @mixin BasicBot
 * @package spec\Kachuru\Vindinium\Bot
 */
class BasicBotSpec extends ObjectBehavior
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

        $this->beConstructedWith(new BotHelper());
    }

    public function it_gets_move()
    {
        $board = $this->buildBoard($this->getBoardWithMines(), 5);

        /**
         *     **
         * $1  ****$-
         *
         * $-      $2
         *
         */

        $this->chooseNextMove($board, new PlayerHero(new BaseHero(1, 'Random', 100, 0, 0, new Position(2, 0))))
            ->shouldReturn('South');
        $this->chooseNextMove($board, new PlayerHero(new BaseHero(1, 'Random', 100, 0, 0, new Position(2, 1))))
            ->shouldReturn('East');
        $this->chooseNextMove($board, new PlayerHero(new BaseHero(1, 'Random', 100, 0, 0, new Position(3, 1))))
            ->shouldReturn('East');
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
