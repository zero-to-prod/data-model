<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Metadata
{
    public const cast = 'cast';
    /**
     * @var string|array{name: string, method: string, include_context: bool}
     */
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