<?php

namespace Zerotoprod\DataModel\Helpers;

/**
 * The `Str` helper class defines constants for pattern matching and type identification
 * within the DataModel package, aiding in parsing and handling type annotations in doc comments.
 *
 * @package Zerotoprod\DataModel\Helpers
 */
class Str
{
    public const types = ['string', 'array', 'int', 'bool', 'object', 'float', 'stdClass', '\stdClass'];
}
