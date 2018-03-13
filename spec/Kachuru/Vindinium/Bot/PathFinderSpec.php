<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BoardTileScore;
use Kachuru\Vindinium\Bot\PathEndPoints;
use Kachuru\Vindinium\Bot\PathFinder;
use Kachuru\Vindinium\Bot\ScoredBoardTile;
use Kachuru\Vindinium\Game\Board;
use Kachuru\Vindinium\Game\BoardTile;
use Kachuru\Vindinium\Game\Position;
use Kachuru\Vindinium\Game\Tile\TileFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class PathFinderSpec
 * @mixin \Kachuru\Vindinium\Bot\PathFinder
 * @package spec\Kachuru\Vindinium\Bot
 */
class PathFinderSpec extends ObjectBehavior
{
    function it_scores_multiple_tiles()
    {
        $board = new Board(new TileFactory(), 5, $this->getEmptyBoard());
        $this->beConstructedWith(
            $board,
            PathEndPoints::buildFromBoard($board, new Position(0, 1), new Position(4, 3))
        );

        $originTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 1)), [0, 6]);

        $this->scoreTiles([$board->getBoardTileAtPosition(new Position(0, 1))])->shouldBeLike(
            [
                $originTile,
            ]
        );

        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........   0, 1   ->   1, 1   ->   2, 1   ->   3, 1   ->   4, 1
         *   ..........                                              ->   4, 2
         *   ........De                                              ->   4, 3
         *   ..........
         *
         * Or => Adjacent Positions:
         *   A:  0, 0 => g=1, h=7, f=8
         *   B:  1, 1 => g=1, h=5, f=6
         *   C:  0, 2 => g=1, h=5, f=6
         * B & C are tied, C is selected as most recently added tile
         */
        $this->scoreTiles($board->getAdjacentBoardTiles(new Position(0, 1)), $originTile)->shouldBeLike(
            [
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 0)), [1, 7], $originTile),
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(1, 1)), [1, 5], $originTile),
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 2)), [1, 5], $originTile),
            ]
        );

        /**
         * 2,2 => Adjacent Positions:
         *   A:  2, 1 => g=4, h=4, f=8
         *   B:  1, 2 => g=4, h=4, f=8
         *   C:  3, 2 => g=4, h=2, f=6
         *   D:  2, 3 => g=4, h=2, f=6
         */
        $parentTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 2)), [3, 2]);

        /**
         * Reality is this isn't what would be checked at this point as the previous tile would probably
         * be in the closed list at this point
         */
        $this->scoreTiles($board->getAdjacentBoardTiles(new Position(2, 2)), $parentTile)->shouldBeLike(
            [
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 1)), [4, 4], $parentTile),
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(1, 2)), [4, 4], $parentTile),
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(3, 2)), [4, 2], $parentTile),
                $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 3)), [4, 2], $parentTile),
            ]
        );
    }

    function it_returns_the_next_step_between_two_points()
    {
        $origin = new Position(0, 1);
        $destination = new Position(4, 3);

        $board = new Board(new TileFactory(), 5, $this->getEmptyBoard());
        $this->beConstructedWith(
            $board,
            PathEndPoints::buildFromBoard($board, $origin, $destination)
        );

        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........   0, 1   ->
         *   **........   0, 2   ->
         *   ********De   0, 3   ->   1, 3   ->   2, 3   ->   3, 3   ->   4, 3
         *   ..........
         *
         * Or => Adjacent Positions:
         *   A:  0, 0 => g=1, h=7, f=8
         *   B:  1, 1 => g=1, h=5, f=6
         *   C:  0, 2 => g=1, h=5, f=6
         * B & C are tied, C is selected as most recently added tile
         */

        $originTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 1)), [0, 6]);

        $moveOne = $this->checkMove($board, new Position(0, 2), [1, 5], $originTile);
        $moveTwo = $this->checkMove($board, new Position(0, 3), [2, 4], $moveOne);
        $moveThree = $this->checkMove($board, new Position(1, 3), [3, 3], $moveTwo);
        $moveFour = $this->checkMove($board, new Position(2, 3), [4, 2], $moveThree);
        $moveFive = $this->checkMove($board, new Position(3, 3), [5, 1], $moveFour);
        $this->checkMove($board, new Position(4, 3), [6, 0], $moveFive);
    }

    function it_finds_the_path_through_empty_space()
    {
        $origin = new Position(0, 1);
        $destination = new Position(4, 3);

        $board = new Board(new TileFactory(), 5, $this->getEmptyBoard());
        $this->beConstructedWith(
            $board,
            PathEndPoints::buildFromBoard($board, $origin, $destination)
        );

        $originTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 1)), [0, 6]);
        $moveOne = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 2)), [1, 5], $originTile);
        $moveTwo = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 3)), [2, 4], $moveOne);
        $moveThree = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(1, 3)), [3, 3], $moveTwo);
        $moveFour = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 3)), [4, 2], $moveThree);
        $moveFive = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(3, 3)), [5, 1], $moveFour);
        $destinationTile = $this->getScoredBoardTile($board->getBoardTileAtPosition($destination), [6, 0], $moveFive);

        $this->find()->shouldBeLike(
            [
                $originTile,
                $moveOne,
                $moveTwo,
                $moveThree,
                $moveFour,
                $moveFive,
                $destinationTile
            ]
        );
    }

    function it_finds_the_path_with_an_intervening_wall()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........
         *   ******....
         *   ####****De
         *   ..........
         */

        $origin = new Position(0, 1);
        $destination = new Position(4, 3);

        $board = new Board(new TileFactory(), 5, $this->getBoardWithWall());
        $this->beConstructedWith(
            $board,
            PathEndPoints::buildFromBoard($board, $origin, $destination)
        );

        $originTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 1)), [0, 6]);
        $moveOne = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 2)), [1, 5], $originTile);
        $moveTwo = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(1, 2)), [2, 4], $moveOne);
        $moveThree = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 2)), [3, 3], $moveTwo);
        $moveFour = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 3)), [4, 2], $moveThree);
        $moveFive = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(3, 3)), [5, 1], $moveFour);
        $destinationTile = $this->getScoredBoardTile($board->getBoardTileAtPosition($destination), [6, 0], $moveFive);

        $this->find()->shouldBeLike(
            [
                $originTile,
                $moveOne,
                $moveTwo,
                $moveThree,
                $moveFour,
                $moveFive,
                $destinationTile
            ]
        );
    }

    function it_finds_the_path_with_a_dead_end()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   ..........
         *   ..##......
         *   ####..##..
         *   ..........
         */
        $origin = new Position(0, 1);
        $destination = new Position(4, 3);

        $board = new Board(new TileFactory(), 5, $this->getBoardWithDeadEnd());
        $this->beConstructedWith(
            $board,
            PathEndPoints::buildFromBoard($board, $origin, $destination)
        );

        $originTile = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(0, 1)), [0, 6]);
        $moveOne = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(1, 1)), [1, 5], $originTile);
        $moveTwo = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 1)), [2, 4], $moveOne);
        $moveThree = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(2, 2)), [3, 3], $moveTwo);
        $moveFour = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(3, 2)), [4, 2], $moveThree);
        $moveFive = $this->getScoredBoardTile($board->getBoardTileAtPosition(new Position(4, 2)), [5, 1], $moveFour);
        $destinationTile = $this->getScoredBoardTile($board->getBoardTileAtPosition($destination), [6, 0], $moveFive);

        $this->find()->shouldBeLike(
            [
                $originTile,
                $moveOne,
                $moveTwo,
                $moveThree,
                $moveFour,
                $moveFive,
                $destinationTile
            ]
        );
    }



    private function checkMove(Board $board, Position $currentPosition, array $expectScore, ScoredBoardTile $parentTile)
    {
        $move = $this->getScoredBoardTile($board->getBoardTileAtPosition($currentPosition), $expectScore, $parentTile);
        $this->getNextMove(
            $this->scoreTiles($board->getAdjacentBoardTiles($parentTile->getPosition()), $parentTile)
        )->shouldBeLike($move);

        return $move;
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

    function getEmptyBoard()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........   0, 1   ->
         *   **........   0, 2   ->
         *   ********De   0, 3   ->   1, 3   ->   2, 3   ->   3, 3   ->   4, 3
         *   ..........
         */
        return "          "
            . "          "
            . "          "
            . "        $-"
            . "          ";
    }

    function getBoardWithWall()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........
         *   ..........
         *   ####....De
         *   ..........
         */
        return "          "
            . "          "
            . "          "
            . "####    $-"
            . "          ";
    }

    function getBoardWithDeadEnd()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
         *   ..........
         *   Or........
         *   ..##......
         *   ####..##De
         *   ..........
         */
        return "          "
            . "          "
            . "  ##      "
            . "####  ##$-"
            . "          ";
    }
}
