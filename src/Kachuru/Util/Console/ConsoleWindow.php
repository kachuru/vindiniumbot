<?php

namespace Kachuru\Util\Console;

class ConsoleWindow
{
    private $xPos;
    private $yPos;
    private $xSize;
    private $ySize;

    public function __construct(int $xPos, int $yPos, int $xSize, int $ySize)
    {
        $this->xPos = $xPos;
        $this->yPos = $yPos;
        $this->xSize = $xSize;
        $this->ySize = $ySize;
    }

    public function getXPos(): int
    {
        return $this->xPos;
    }

    public function getYPos(): int
    {
        return $this->yPos;
    }

    public function getXSize(): int
    {
        return $this->xSize;
    }

    public function getYSize(): int
    {
        return $this->ySize;
    }
}
