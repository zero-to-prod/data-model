<?php

namespace Tests\Unit\DataModel\Instance;

use Zerotoprod\DataModel\DataModel;

class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const name = 'name';
    public int $id;
    public string $name;

    public function __construct(array $data = [])
    {
        self::from($data, $this);
    }
}