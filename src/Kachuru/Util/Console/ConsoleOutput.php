<?php

namespace Kachuru\Util\Console;

class ConsoleOutput
{
    private $windows;
    private $height = 0;

    const SHELL_ESCAPE = "\033[";

    public function prepare(array $windows): ConsoleOutput
    {
        $this->windows = $windows;

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
        if (array_key_exists($window, $this->windows)) {
            $window = $this->windows[$window];
            /**
             * @var $window ConsoleWindow
             */
            $lines = explode(PHP_EOL, (string) $content);

            for ($i = 0; $i < count($lines); $i++) {
                $this->positionCursor($window->getXPos() + 1, $window->getYPos() + 1 + $i);

                $this->writeLine($lines[$i], $window);
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
            echo self::SHELL_ESCAPE . $n . $d;
        }
    }

    private function positionCursor($xPos, $yPos)
    {
        echo self::SHELL_ESCAPE ."{$yPos};{$xPos}H";
    }

    private function clearLine()
    {
        echo self::SHELL_ESCAPE ."K";
    }

    private function clearScreen()
    {
        echo self::SHELL_ESCAPE ."2J";
    }

    private function writeLine($line, ConsoleWindow $window)
    {
        print(str_pad($line, $window->getXSize() - 2, ' '));
    }
}
