<?php

namespace Kachuru\Util;

class ConsoleOutput
{
    private $windows;
    private $height = 0;

    public function __construct(array $windows)
    {
        $this->windows = $windows;
    }

    /**
     * Draw the frames for all the windows
     */
    public function prepare(): ConsoleOutput
    {
        $this->clearScreen();

        foreach ($this->windows as $window) {
            $height = $window->getYPos() + $window->getYSize();

            if ($height > $this->height) {
                $this->height = $height;
            }

            /**
             * @var $window ConsoleWindow
             */
            $this->positionCursor($window->getXPos(), $window->getYPos());
            $this->writeHr($window->getXPos(), $window->getXSize());

            for ($i = 1; $i <= $window->getYSize(); $i++) {
                $this->positionCursor($window->getXPos(), $window->getYPos()+$i);
                $this->writePaddedLine($window->getXPos(), $window->getXSize());
            }

            $this->positionCursor($window->getXPos(), $window->getYPos()+$i);
            $this->writeHr($window->getXPos(), $window->getXSize());
        }

        return $this;
    }

    public function write($window, $content)
    {
        if (isset($this->windows[$window])) {
            $window = $this->windows[$window];
            /**
             * @var $window ConsoleWindow
             */
            $lines = explode(PHP_EOL, $content);

            for ($i = 0; $i < count($lines); $i++) {
                $this->positionCursor($window->getXPos() + 1, $window->getYPos() + 1 + $i);
                print(str_pad($lines[$i], $window->getXSize() - 2, ' '));
            }

            $this->positionCursor(1, $this->height + 2);
        }
    }



    private function writeHr($xPos, $length)
    {
        $this->writePaddedLine($xPos, $length, '+', '-');
    }

    private function writePaddedLine($xPos, $length, $endChar = '|', $padChar = ' ')
    {
        printf('%s%s%s' . PHP_EOL, $endChar, str_pad('', $length, $padChar), $endChar);
    }

    private function cursorUp($n = 1)
    {
        $this->moveCursor($n, 'A');
    }

    private function cursorDown($n = 1)
    {
        $this->moveCursor($n, 'B');
    }

    private function cursorRight($n = 1)
    {
        $this->moveCursor($n, 'C');
    }

    private function cursorLeft($n = 1)
    {
        $this->moveCursor($n, 'D');
    }

    private function moveCursor($n, $d)
    {
        if ($n > 0) {
            echo "\033[" . $n . $d;
        }
    }

    private function positionCursor($xPos, $yPos)
    {
        echo "\033[{$yPos};{$xPos}H";
    }

    private function clearLine()
    {
        echo "\033[K";
    }

    private function clearScreen()
    {
        echo "\033[2J";
    }
}
