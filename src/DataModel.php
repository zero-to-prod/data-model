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
        $ReflectionClass = new ReflectionClass($self);

        $ClassAttribute = current(
            array_filter(
                $ReflectionClass->getAttributes(),
                static fn($Attr) => $Attr->getName() === Metadata::class
            )
        );
        $Metadata = $ClassAttribute ? $ClassAttribute->newInstance() : null;

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            $property = $ReflectionProperty->getName();
            $value = $context[$property] ?? null;

            if (is_callable([$self, $property])) {
                $self->{$property} = $self->{$property}($value, $context);
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();

            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property} = $value;
                continue;
            }

            $Attribute = $ReflectionProperty->getAttributes(Describe::class)[0] ?? null;
            $Describe = $Attribute ? $Attribute->newInstance() : null;

            if ($Describe && isset($Describe->target)) {
                $args = [$value];
                if (!($Describe->exclude_context ?? false)) {
                    $args[] = $context;
                }

                $target = $Describe->target;
                if (is_callable([$target, 'parse'])) {
                    $self->{$property} = $target::parse(...$args);
                    continue;
                }

                $self->{$property} = $target(...$args);
                continue;
            }

            if (!array_key_exists($property, $context)) {
                if ($Describe && ($Describe->required ?? false)) {
                    throw new PropertyRequired('Property: '.$property.' is required');
                }
                continue;
            }

            $type = $ReflectionType->getName();

            if ($Metadata && isset($Metadata->cast[$type])) {
                $self->{$property} = ($Metadata->cast[$type])($value);
                continue;
            }

            if (in_array($type, Str::types, true)) {
                $self->{$property} = $value;
                continue;
            }

            if (is_callable([$type, 'from'])) {
                $self->{$property} = $type::from($value);
                continue;
            }

            $self->{$property} = $value;
        }

        return $self;
    }
}
