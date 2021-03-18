<?php

declare(strict_types=1);

namespace App\Animals;

use ArrayIterator;
use IteratorAggregate;

class Animals implements IteratorAggregate
{
    private array $elements;

    public function __construct(Animal ...$animals)
    {
        $this->elements = $animals;
    }

    public function getIterator(): iterable
    {
        return new ArrayIterator($this->elements);
    }
}
