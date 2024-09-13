<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Describe
{
    public const parse = 'parse';
    public const _class = 'class';
    public const name = 'name';
    public const method = 'method';
    public const include_context = 'include_context';
    /**
     * @var string|array{name: string, method: string, include_context: bool}
     */
    public string|array $class;
    public bool $strict;
    public string $via;
    public string $map_from;
    public bool $require_typed_properties;
    public string $output_as;

    /**
     * @param  string|null|array{
     *      class?: string|array{name: string, method: string, include_context: bool},
     *      function?: string|array{name: string, include_context: bool},
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