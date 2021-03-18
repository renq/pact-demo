<?php

declare(strict_types=1);

namespace App\Animals;

class Animal
{
    private string $name;
    private string $species;

    public function __construct(string $name, string $species)
    {
        $this->name = $name;
        $this->species = $species;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }
}
