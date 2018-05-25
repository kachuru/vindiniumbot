<?php

namespace Kachuru\Util\Console;

class ConsoleContent
{
    const CONSOLE_COLORS = [
        self::CONSOLE_COLOR_BLACK => 30,
        self::CONSOLE_COLOR_RED => 31,
        self::CONSOLE_COLOR_GREEN => 32,
        self::CONSOLE_COLOR_YELLOW => 33,
        self::CONSOLE_COLOR_BLUE => 34,
        self::CONSOLE_COLOR_MAGENTA => 35,
        self::CONSOLE_COLOR_CYAN => 36,
        self::CONSOLE_COLOR_WHITE => 37,
    ];

    const CONSOLE_COLOR_BLACK = 'black';
    const CONSOLE_COLOR_RED = 'red';
    const CONSOLE_COLOR_GREEN = 'green';
    const CONSOLE_COLOR_YELLOW = 'yellow';
    const CONSOLE_COLOR_BLUE = 'blue';
    const CONSOLE_COLOR_MAGENTA = 'magenta';
    const CONSOLE_COLOR_CYAN = 'cyan';
    const CONSOLE_COLOR_WHITE = 'white';

    const CONSOLE_STYLES = [
        self::CONSOLE_STYLE_BOLD => 1,
        self::CONSOLE_STYLE_INVERSE => 7,
        self::CONSOLE_STYLE_UNDERLINE => 4
    ];

    const CONSOLE_STYLE_BOLD = 'bold';
    const CONSOLE_STYLE_UNDERLINE = 'underline';
    const CONSOLE_STYLE_INVERSE = 'inverse';

    private $text;
    private $color;
    private $style;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return (!is_null($this->style) || !is_null($this->color))
            ? sprintf("\033[%d;%dm%s\033[0m", $this->getStyleCode(), $this->getColorCode(), $this->text)
            : $this->text;
    }

    public function black(): self
    {
        $this->color = self::CONSOLE_COLOR_BLACK;

        return $this;
    }

    public function red(): self
    {
        $this->color = self::CONSOLE_COLOR_RED;

        return $this;
    }

    public function green(): self
    {
        $this->color = self::CONSOLE_COLOR_GREEN;

        return $this;
    }

    public function yellow(): self
    {
        $this->color = self::CONSOLE_COLOR_YELLOW;

        return $this;
    }

    public function blue(): self
    {
        $this->color = self::CONSOLE_COLOR_BLUE;

        return $this;
    }

    public function magenta(): self
    {
        $this->color = self::CONSOLE_COLOR_MAGENTA;

        return $this;
    }

    public function cyan(): self
    {
        $this->color = self::CONSOLE_COLOR_CYAN;

        return $this;
    }

    public function white(): self
    {
        $this->color = self::CONSOLE_COLOR_WHITE;

        return $this;
    }

    public function bold(): self
    {
        $this->style = self::CONSOLE_STYLE_BOLD;

        return $this;
    }

    public function underline(): self
    {
        $this->style = self::CONSOLE_STYLE_UNDERLINE;

        return $this;
    }

    public function invert(): self
    {
        $this->style = self::CONSOLE_STYLE_INVERSE;

        return $this;
    }

    private function getStyleCode(): int
    {
        return is_null($this->style)
            ? 0
            : self::CONSOLE_STYLES[$this->style];
    }

    private function getColorCode(): int
    {
        return is_null($this->color)
            ? 0
            : self::CONSOLE_COLORS[$this->color];
    }
}
