<?php

namespace Tests\Unit\DataModel\Recursion;

use stdClass;
use Zerotoprod\DataModel\DataModel;

class BaseClass
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

    /** @var int $id */
    public $id;
    /** @var string $name */
    public $name;
    /** @var float $price */
    public $price;
    /** @var bool $is_free */
    public $is_free;
    /** @var array $list */
    public $list;
    /** @var object $object */
    public $object;
    /** @var stdClass $stdClass */
    public $stdClass;
    /** @var mixed $mixed */
    public $mixed;
    /**
     * @var \Tests\Unit\DataModel\Recursion\Child $Child
     */
    public $Child;
    /**
     * @var ShortNamespaceChild $ShortNamespaceChild
     */
    public $ShortNamespaceChild;
}