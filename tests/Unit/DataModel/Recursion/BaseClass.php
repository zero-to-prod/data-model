<?php

namespace Tests\Unit\DataModel\Recursion;

use stdClass;
use Zerotoprod\DataModel\DataModel;

readonly class BaseClass
{
    use DataModel;

    public const id = 'id';
    public const name = 'name';
    public const price = 'price';
    public const is_free = 'is_free';
    public const list = 'list';
    public const object = 'object';
    public const stdClass = 'stdClass';
    public const mixed = 'mixed';
    public const Child = 'Child';
    public const ShortNamespaceChild = 'ShortNamespaceChild';

    public int $id;
    public string $name;
    public float $price;
    public bool $is_free;
    public array $list;
    public object $object;
    public stdClass $stdClass;
    public mixed $mixed;
    public \Tests\Unit\DataModel\Recursion\Child $Child;
    public ShortNamespaceChild $ShortNamespaceChild;
}