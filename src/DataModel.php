<?php

namespace Zerotoprod\DataModel;

use ReflectionClass;
use ReflectionUnionType;
use Zerotoprod\DataModel\Helpers\Str;

/**
 * The `DataModel` trait creates class instances from arrays, strings, or objects,
 * automatically casting types based on PHPDoc annotations. It simplifies populating
 * class properties by using reflection to match data with annotated types.
 *
 * @package Zerotoprod\DataModel
 */
trait DataModel
{
    /**
     * Instantiates the class from an array, string, or object, casting values based on PHPDoc annotations.
     * Uses reflection to match data with property types, supporting primitives and classes with a `from` method.
     *
     * Example:
     * ```
     * MyClass::from(['name' => 'John Doe']);
     * MyClass::from($stdClass);
     * ```
     *
     * @param  iterable|object|null  $value  Data to populate class properties.
     */
    public static function from(iterable|object|null $value = null): self
    {
        if (!$value) {
            return new self();
        }

        if ($value instanceof self) {
            return $value;
        }

        $value = is_object($value) ? (array)$value : $value;

        $self = new self();

        foreach ((new ReflectionClass($self))->getProperties() as $ReflectionProperty) {
            $property = $ReflectionProperty->getName();
            $ReflectionType = $ReflectionProperty->getType();

            // Skip if no type or if it's a union type
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property} = $value[$property];
                continue;
            }
            $Attribute = current(array_filter(
                $ReflectionProperty->getAttributes(),
                static fn($Attr) => $Attr->getName() === Describe::class
            ));

            if ($Attribute) {
                $Describe = new Describe(...$Attribute->getArguments());
            }

            if ($Attribute && isset($Describe->class)) {
                $include_context = $Describe->class[Describe::include_context] ?? false;
                $method = $Describe->class[Describe::method] ?? Describe::parse;

                if (is_callable([$Describe->class, $method])) {
                    $self->{$property} = $Describe->class::$method($value[$property], $include_context ? $value : []);
                    continue;
                }

                if (is_array($Describe->class) && isset($Describe->class[Describe::name])) {
                    $self->{$property} = ($Describe->class[Describe::name])::$method($value[$property], $include_context ? $value : []);

                    continue;
                }
            }

            // Reject
            if (!array_key_exists($property, $value)) {
                if (isset($Describe->required) && $Describe->required) {
                    throw new PropertyRequired('Property '.$property.' is required');
                }
                continue;
            }

            $type = $ReflectionType->getName();

            // Handle primitive types
            if (in_array($type, Str::types, true)) {
                $self->{$property} = $value[$property];
                continue;
            }

            // Handle class types with a 'from' method
            if (is_callable([$type, 'from'])) {
                $self->{$property} = $type::from($value[$property]);
                continue;
            }

            $self->{$property} = $value[$property];
        }

        return $self;
    }
}
