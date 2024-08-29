<?php

namespace Tests\Unit\Iterable;

use Iterator;

class MyIterable implements Iterator
{
    private $items = [];
    private $position = 0;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }
}
