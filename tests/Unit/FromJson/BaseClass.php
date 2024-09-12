<?php

namespace Tests\Unit\FromJson;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\FromJson;

readonly class BaseClass
{
    use DataModel;
    use FromJson;

    public const id = 'id';
    public const name = 'name';
    public int $id;
    public string $name;
}