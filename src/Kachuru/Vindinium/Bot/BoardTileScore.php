<?php

namespace Kachuru\Vindinium\Bot;

class BoardTileScore
{
    private $moveCost;
    private $distanceToDestination;

    public function __construct($moveCost, $distanceToDestination)
    {
        $this->moveCost = $moveCost;
        $this->distanceToDestination = $distanceToDestination;
    }

    public function getMoveCost()
    {
        return $this->moveCost;
    }

    public function getDistanceToDestination()
    {
        return $this->distanceToDestination;
    }

    public function getScore()
    {
        return $this->moveCost + $this->distanceToDestination;
    }
}
