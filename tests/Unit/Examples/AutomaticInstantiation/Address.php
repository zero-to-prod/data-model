<?php

namespace Tests\Unit\Examples\AutomaticInstantiation;

use Tests\Unit\Examples\ExtendsTrait\DataModel;

readonly class Address
{
    use DataModel;

    public string $street;
    public string $city;
}