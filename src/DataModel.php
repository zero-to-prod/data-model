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
        if ($value instanceof self) {
            return $value;
        }

        if (is_object($value)) {
            $value = (array)$value;
        }

        $self = new self();

        foreach ((new ReflectionClass($self))->getProperties() as $ReflectionProperty) {
            $property = $ReflectionProperty->getName();

            if (!array_key_exists($property, $value)) {
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();

            // Skip if no type or if it's a union type
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property} = $value[$property];
                continue;
            }

            foreach ($ReflectionProperty->getAttributes() as $Attribute) {
                if ($Attribute->getName() === Describe::class) {
                    $Describe = new Describe(...$Attribute->getArguments());
                    if (isset($Describe->class)) {
                        $include_context = $Describe->class[Describe::include_context] ?? false;
                        $method = $Describe->class[Describe::method] ?? Describe::parse;

                        if (is_callable([$Describe->class, $method])) {
                            $self->{$property} = $include_context
                                ? $Describe->class::$method($value[$property], $value)
                                : $Describe->class::$method($value[$property]);
                            continue 2;
                        }

                        if (is_array($Describe->class) && isset($Describe->class[Describe::name])) {
                            $class = $Describe->class[Describe::name];
                            $self->{$property} = $include_context
                                ? $class::$method($value[$property], $value)
                                : $class::$method($value[$property]);
                            continue 2;
                        }
                    }
                }
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
