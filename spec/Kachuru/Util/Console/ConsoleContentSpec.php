<?php

namespace spec\Kachuru\Util\Console;

use Kachuru\Util\Console\ConsoleContent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConsoleContentSpec extends ObjectBehavior
{
    function it_renders_the_text()
    {
        $this->beConstructedWith('The quick brown fox jumped over the lazy dog');

        $this->__toString()->shouldReturn('The quick brown fox jumped over the lazy dog');
    }

    function it_renders_the_color_text()
    {
        $this->beConstructedWith('The red cape');
        $this->magenta()->bold();
        $this->__toString()->shouldReturn("\033[1;35mThe red cape\033[0m");
    }
}
