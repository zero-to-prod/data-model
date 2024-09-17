<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Metadata
{

    public string|array $cast;

    /**
     * @param  array{
     *      cast?: array,
     * }  $attributes
     */
    public function __construct(array $attributes)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}