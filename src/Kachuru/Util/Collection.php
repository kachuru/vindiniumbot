<?php

namespace Kachuru\Util;

use IteratorIterator;

class Collection extends IteratorIterator
{
    private $items;

    public function __construct(array $items)
    {
        array_walk(
            $items,
            function ($item) {
                $class = static::SET_TYPE;
                if (!$item instanceof $class) {
                    throw new \InvalidArgumentException(sprintf(
                        'Collection %s expects instance of %s, %s found.',
                        static::class,
                        $class,
                        get_class($item)
                    ));
                }
            }
        );

        $this->items = $items;
    }

    public function findBy($call, $value)
    {
        return array_reduce(
            $this->items,
            function ($last, $item) use ($call, $value) {
                if (call_user_func([$item, $call], $value) == $value) {
                    $last = $item;
                }

                return $last;
            }
        );
    }
}
