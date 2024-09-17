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
     * @param  iterable|object|null  $context  Data to populate class properties.
     */
    public static function from(iterable|object|null $context = null): self
    {
        if (!$context) {
            return new self();
        }

        if ($context instanceof self) {
            return $context;
        }

        $context = is_object($context) ? (array)$context : $context;

        $self = new self();
        $ReflectionClass = (new ReflectionClass($self));

        $ClassAttribute = current(
            array_filter(
                $ReflectionClass->getAttributes(),
                static fn($Attr) => $Attr->getName() === Metadata::class
            )
        );
        $Metadata = $ClassAttribute ? new Metadata(...$ClassAttribute->getArguments()) : null;

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            $property = $ReflectionProperty->getName();

            // Call method named the same as property
            if (is_callable([$self, $property])) {
                $self->{$property} = call_user_func([$self, $property], $context[$property], $context);
                continue;
            }
            $ReflectionType = $ReflectionProperty->getType();

            // Skip if no type or if it's a union type
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property} = $context[$property];
                continue;
            }

            $Attribute = current(
                array_filter(
                    $ReflectionProperty->getAttributes(),
                    static fn($Attr) => $Attr->getName() === Describe::class
                )
            );

            if ($Attribute) {
                $Describe = new Describe(...$Attribute->getArguments());

                if (isset($Describe->target)) {
                    $args = [$context[$property]];
                    if (!($Describe->exclude_context ?? false)) {
                        $args[] = $context;
                    }

                    $self->{$property} = is_callable([$Describe->target, Describe::parse])
                        ? call_user_func([$Describe->target, Describe::parse], ...$args)
                        : call_user_func($Describe->target, ...$args);

                    continue;
                }
            }

            // Reject if property is required.
            if (!array_key_exists($property, $context)) {
                if (isset($Describe->required) && $Describe->required) {
                    throw new PropertyRequired('Property: '.$property.' is required');
                }
                continue;
            }

            $type = $ReflectionType->getName();

            // Metadata
            if (isset($Metadata->cast[$type])) {
                $self->{$property} = call_user_func($Metadata?->cast[$type], $context[$property]);
                continue;
            }

            // Primitive types
            if (in_array($type, Str::types, true)) {
                $self->{$property} = $context[$property];
                continue;
            }

            // Instantiate 'from' method
            if (is_callable([$type, 'from'])) {
                $self->{$property} = call_user_func([$type, 'from'], $context[$property]);
                continue;
            }

            $self->{$property} = $context[$property];
        }

        return $self;
    }
}
