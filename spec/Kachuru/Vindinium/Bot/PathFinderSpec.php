<?php

namespace spec\Kachuru\Vindinium\Bot;

use Kachuru\Vindinium\Bot\BoardTileScore;
use Kachuru\Vindinium\Bot\PathEndPoints;
use Kachuru\Vindinium\Bot\PathFinder;
use Kachuru\Vindinium\Bot\ScoredBoardTile;
use Kachuru\Vindinium\Game\Board;
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
            new PathEndPoints(
                $board->getBoardTileAtPosition(new Position(0, 1)),
                $board->getBoardTileAtPosition(new Position(4, 3))
            )
        );

        $originTile = new ScoredBoardTile(
            $board->getBoardTileAtPosition(new Position(0, 1)),
            new BoardTileScore(0, 6)
        );

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
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(0, 0)),
                    new BoardTileScore(1, 7),
                    $originTile
                ),
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(1, 1)),
                    new BoardTileScore(1, 5),
                    $originTile
                ),
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(0, 2)),
                    new BoardTileScore(1, 5),
                    $originTile
                ),
            ]
        );

        /**
         * 2,2 => Adjacent Positions:
         *   A:  2, 1 => g=4, h=4, f=8
         *   B:  1, 2 => g=4, h=4, f=8
         *   C:  3, 2 => g=4, h=2, f=6
         *   D:  2, 3 => g=4, h=2, f=6
         */
        $parentTile = new ScoredBoardTile(
            $board->getBoardTileAtPosition(new Position(2, 2)),
            new BoardTileScore(3, 2)
        );
        /**
         * Reality is this isn't what would be checked at this point as the previous tile would probably
         * be in the closed list at this point
         */
        $this->scoreTiles($board->getAdjacentBoardTiles(new Position(2, 2)), $parentTile)->shouldBeLike(
            [
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(2, 1)),
                    new BoardTileScore(4, 4),
                    $parentTile
                ),
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(1, 2)),
                    new BoardTileScore(4, 4),
                    $parentTile
                ),
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(3, 2)),
                    new BoardTileScore(4, 2),
                    $parentTile
                ),
                new ScoredBoardTile(
                    $board->getBoardTileAtPosition(new Position(2, 3)),
                    new BoardTileScore(4, 2),
                    $parentTile
                ),
            ]
        );
    }

    function it_returns_the_next_step_between_two_points()
    {
        $board = new Board(new TileFactory(), 5, $this->getEmptyBoard());
        $this->beConstructedWith(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition(new Position(0, 1)),
                $board->getBoardTileAtPosition(new Position(4, 3))
            )
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

        $startPosition = new Position(0, 1);

        $originTile = new ScoredBoardTile(
            $board->getBoardTileAtPosition($startPosition),
            new BoardTileScore(0, 6)
        );

        $positionOne = new Position(0, 2);
        $moveOne = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionOne),
            new BoardTileScore(1, 5),
            $originTile
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($startPosition), $originTile))->shouldBeLike($moveOne);

        $positionTwo = new Position(0, 3);
        $moveTwo = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionTwo),
            new BoardTileScore(2, 4),
            $moveOne
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($positionOne), $moveOne))->shouldBeLike($moveTwo);

        $positionThree = new Position(1, 3);
        $moveThree = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionThree),
            new BoardTileScore(3, 3),
            $moveTwo
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($positionTwo), $moveTwo))->shouldBeLike($moveThree);

        $positionFour = new Position(2, 3);
        $moveFour = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionFour),
            new BoardTileScore(4, 2),
            $moveThree
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($positionThree), $moveThree))->shouldBeLike($moveFour);

        $positionFive = new Position(3, 3);
        $moveFive = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionFive),
            new BoardTileScore(5, 1),
            $moveFour
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($positionFour), $moveFour))->shouldBeLike($moveFive);

        $positionSix = new Position(4, 3);
        $moveSix = new ScoredBoardTile(
            $board->getBoardTileAtPosition($positionSix),
            new BoardTileScore(6, 0),
            $moveFive
        );
        $this->getNextMove($this->scoreTiles($board->getAdjacentBoardTiles($positionFive), $moveFive))->shouldBeLike($moveSix);
    }

    function it_finds_the_path_the_path_between_two_points()
    {
        $origin = new Position(0, 1);
        $destination = new Position(2, 2);

        $board = new Board(new TileFactory(), 5, $this->getEmptyBoard());
        $this->beConstructedWith(
            $board,
            new PathEndPoints(
                $board->getBoardTileAtPosition($origin),
                $board->getBoardTileAtPosition($destination)
            )
        );

        $originTile = new ScoredBoardTile(
            $board->getBoardTileAtPosition($origin),
            new BoardTileScore(0, 3)
        );

        $moveOne = new ScoredBoardTile(
            $board->getBoardTileAtPosition(new Position(0, 2)),
            new BoardTileScore(1, 2),
            $originTile
        );

        $moveTwo = new ScoredBoardTile(
            $board->getBoardTileAtPosition(new Position(1, 2)),
            new BoardTileScore(2, 1),
            $moveOne
        );

        $destinationTile = new ScoredBoardTile(
            $board->getBoardTileAtPosition($destination),
            new BoardTileScore(3, 0),
            $moveTwo
        );

        $this->find()->shouldBeLike(
            [
                $originTile,
                $moveOne,
                $moveTwo,
                $destinationTile
            ]
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

        $board = "          "
            . "          "
            . "          "
            . "          "
            . "          ";

        return $board;
    }

    function getBoardWithWall()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
..........
SS****##..
....**##..
....**##FF
....******
         */

        $board = <<<TEXT
..........
......##..
......##..
......##..
..........
TEXT;
    }

    function getBoardWithDeadEnd()
    {
        /**
         * Starting at 0,1 and moving to 4,3 should yield this path:
....####..
SS**XX##..
..**####..
..******FF
..........
         */

        $board = <<<TEXT
....####..
......##..
....####..
..........
..........
TEXT;
    }
}
