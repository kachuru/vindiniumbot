<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BoardTileScore;
use Kachuru\Vindinium\Bot\BoardTileScorer;
use Kachuru\Vindinium\Bot\ScoredBoardTile;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use Kachuru\Vindinium\Game\Tile\Tile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BoardTileScorerSpec
 * @mixin BoardTileScorer
 * @package spec\Kachuru\Vindinium\Bot
 */
class BoardTileScorerSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            $this->getEmptyBoardTile(new Position(0, 1)),
            $this->getEmptyBoardTile(new Position(4, 3))
        );
    }

    function it_calculates_the_distance_between_two_board_tiles()
    {
        $this->estimateCostToDestination($this->getEmptyBoardTile(new Position(3, 3)))->shouldReturn(1);
        $this->estimateCostToDestination($this->getEmptyBoardTile(new Position(4, 4)))->shouldReturn(1);
        $this->estimateCostToDestination($this->getEmptyBoardTile(new Position(3, 2)))->shouldReturn(2);
        $this->estimateCostToDestination($this->getEmptyBoardTile(new Position(1, 1)))->shouldReturn(5);
    }

    function it_scores_board_tile()
    {
        $boardTileOne = $this->getEmptyBoardTile(new Position(1, 1));
        $resultTile = new ScoredBoardTile(
            $boardTileOne,
            new BoardTileScore(1, 5)
        );
        $this->scoreBoardTile($boardTileOne)->shouldBeLike($resultTile);

        $boardTileTwo = $this->getEmptyBoardTile(new Position(2, 1));
        $this->scoreBoardTile($boardTileTwo, $resultTile)->shouldBeLike(
            new ScoredBoardTile(
                $boardTileTwo,
                new BoardTileScore(2, 4),
                $resultTile
            )
        );
    }

    private function getEmptyBoardTile(Position $position): BoardTile
    {
        return new BoardTile(New Tile(new EmptyTile(), '  '), $position);
    }
}
