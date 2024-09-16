<?php

namespace Zerotoprod\DataModel;

use Attribute;

#[Attribute]
readonly class Describe
{
    public const parse = 'parse';
    public const cast = 'cast';
    public const name = 'name';
    public const method = 'method';
    public const include_context = 'include_context';
    /**
     * @var string|array{name: string, method: string, include_context: bool}
     */
    public string|array $cast;
    public bool $required;
    public string $via;
    public string $map_from;
    public bool $require_typed_properties;
    public string $output_as;
    public string $method;

    /**
     * @param  string|null|array{
     *      cast?: string|array{name: string, method: string, include_context: bool},
     *      function?: string|array{name: string, include_context: bool},
     *      required?: bool,
     *      via?: string,
     *      map_from?: string,
     *      require_typed_properties?: bool,
     *      output_as?: string,
     * }  $attributes
     */
    public function __construct(string|null|array $attributes)
    {
        if (is_string($attributes)) {
            $this->method = $attributes;

            return;
        }

        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}