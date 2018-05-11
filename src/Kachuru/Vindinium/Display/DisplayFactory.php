<?php

namespace Kachuru\Vindinium\Display;

class DisplayFactory
{
    private $displays = [];

    public function addDisplay(Display $display)
    {
        $this->displays[$display->getHandle()] = $display;
    }

    public function getDisplay($displayHandle): Display
    {
        return $this->displays[$displayHandle];
    }
}
