<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Describe
{
    public ?string $from;
    public bool $strict;
    public ?string $via;
    public ?string $map_from;
    public bool $require_typed_properties;
    public ?string $output_as;

    /**
     * @param  string|null|array{
     *      from?: string,
     *      strict?: bool,
     *      via?: string,
     *      map_from?: string,
     *      require_typed_properties?: bool,
     *      output_as?: string,
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