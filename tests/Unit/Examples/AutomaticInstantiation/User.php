<?php

namespace Tests\Unit\Examples\AutomaticInstantiation;

use Tests\Unit\Examples\ExtendsTrait\DataModel;

class User
{
    use DataModel;

    public string $name;
    public Address $address;
}