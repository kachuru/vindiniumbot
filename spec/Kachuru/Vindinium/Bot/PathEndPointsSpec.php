<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BoardTileScore;
use Kachuru\Vindinium\Bot\PathEndPoints;
use Kachuru\Vindinium\Bot\ScoredBoardTile;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\EmptyTile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BoardTileScorerSpec
 * @mixin PathEndPoints
 * @package spec\Kachuru\Vindinium\Bot
 */
class PathEndPointsSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            $this->getEmptyBoardTile(new Position(0, 1)),
            [$this->getEmptyBoardTile(new Position(4, 3))]
        );
    }

    /**
     * Test the estimateCostToDestination call
     * Commented out as we made this private.
     */
    function it_calculates_the_distance_between_two_board_tiles()
    {

        $this->estimateCostToOrigin($this->getEmptyBoardTile(new Position(0, 0)))->shouldReturn(1);
        $this->estimateCostToOrigin($this->getEmptyBoardTile(new Position(1, 1)))->shouldReturn(1);
        $this->estimateCostToOrigin($this->getEmptyBoardTile(new Position(1, 2)))->shouldReturn(2);
        $this->estimateCostToOrigin($this->getEmptyBoardTile(new Position(3, 3)))->shouldReturn(5);
    }

    function it_scores_board_tile()
    {
        $originTile = $this->getScoredBoardTile($this->getEmptyBoardTile(new Position(0, 1)), [0, 6]);

        $boardTileOne = $this->getEmptyBoardTile(new Position(4, 2));
        $resultTile = $this->getScoredBoardTile($boardTileOne, [1, 5], $originTile);
        $this->scoreBoardTile($boardTileOne, $originTile)->shouldBeLike($resultTile);

        $boardTileTwo = $this->getEmptyBoardTile(new Position(4, 1));
        $this->scoreBoardTile($boardTileTwo, $resultTile)->shouldBeLike(
            $this->getScoredBoardTile($boardTileTwo, [2, 4], $resultTile)
        );
    }

    private function getEmptyBoardTile(Position $position): BoardTile
    {
        return new BoardTile($position, new EmptyTile());
    }

    private function getScoredBoardTile(
        BoardTile $boardTile,
        array $score = [0, 0],
        ScoredBoardTile $parentTile = null
    ): ScoredBoardTile {
        return new ScoredBoardTile(
            $boardTile,
            new BoardTileScore($score[0], $score[1]),
            $parentTile
        );
    }
}
