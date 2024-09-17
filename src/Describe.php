<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Describe
{
    public string|array $target;
    public bool $required;

    /**
     * @param  string|null|array{
     *      target?: string|array{string, string},
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