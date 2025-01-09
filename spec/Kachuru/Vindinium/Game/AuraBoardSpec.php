<?php

namespace spec\Kachuru\Vindinium\Game;

use Kachuru\Vindinium\Game\AuraBoard;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Kachuru\Vindinium\SpecHelper;

/**
 * Class AuraBoardSpec
 * @mixin AuraBoard
 * @package spec\Kachuru\Vindinium\Game
 */
class AuraBoardSpec extends ObjectBehavior
{
    /**
     * @var SpecHelper
     */
    private $specHelper;

    public function __construct(SpecHelper $specHelper)
    {
        $this->specHelper = $specHelper;
    }

    function it_produces_an_auromatic_board()
    {
        $this->beConstructedWith($this->specHelper->buildM1Board($this->specHelper->getHeroes()));

        /**
         * ## @1       ## ##       @4 ##
         *    00 05 05       05 25 25
         *          ## ## ## ##
         * 05 05 05             05 25 05
         *             ## ##
         * 05 05 03 05       05 03 05 05
         *       []             []
         * 03 03 01 03 05 05 03 01 03 03
         * $-       ##       ##       $-
         * 01 03 03    05 05    03 03 01
         * $-       ##       ##       $-
         * 01 03 03    05 05    03 03 01
         *       []             []
         * 03 03 01 03 05 05 03 21 03 03
         *             ## ##    @3
         * 05 05 03 05       25 23 25 05
         *          ## ## ## ##
         * 05 25 05             25 05 05
         * ## @2       ## ##          ##
         *    25 25 05       05 05 05
         */
    }

}
