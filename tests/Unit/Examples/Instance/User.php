<?php

namespace Tests\Unit\Examples\Instance;

use Zerotoprod\DataModel\DataModel;

class User
{
    use DataModel;

    public string $name;

    public function __construct(array $data = [])
    {
        self::from($data, $this);
    }
}