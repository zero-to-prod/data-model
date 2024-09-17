<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Describe
{
    public string|array $cast;
    public bool $required;

    /**
     * @param  string|null|array{
     *      cast?: array|string,
     *      exclude_context?: bool,
     *      required?: bool,
     * }  $attributes
     */
    public function __construct(string|null|array $attributes)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}