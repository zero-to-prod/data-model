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
        /** @var Metadata|null $Metadata */
        $Metadata = $ClassAttribute ? $ClassAttribute->newInstance() : null;

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            $property = $ReflectionProperty->getName();

            if (is_callable([$self, $property])) {
                $self->{$property} = $self->{$property}($context[$property], $context);
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();

            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property} = $context[$property];
                continue;
            }

            $Attribute = $ReflectionProperty->getAttributes(Describe::class)[0] ?? null;
            /** @var Describe $Describe */
            $Describe = $Attribute ? $Attribute->newInstance() : null;

            if ($Describe && isset($Describe->target)) {
                $args = [$context[$property]];
                if (!($Describe->exclude_context ?? false)) {
                    $args[] = $context;
                }

                if (is_callable([$Describe->target, 'parse'])) {
                    $self->{$property} = ($Describe->target)::parse(...$args);
                    continue;
                }

                $self->{$property} = ($Describe->target)(...$args);
                continue;
            }

            if (!array_key_exists($property, $context)) {
                if ($Describe->required ?? false) {
                    throw new PropertyRequired('Property: '.$property.' is required');
                }
                continue;
            }

            $type = $ReflectionType->getName();

            if ($Metadata?->targets[$type] ?? false) {
                $self->{$property} = ($Metadata->targets[$type])($context[$property]);
                continue;
            }

            if (in_array($type, Str::types, true)) {
                $self->{$property} = $context[$property];
                continue;
            }

            if (is_callable([$type, 'from'])) {
                $self->{$property} = $type::from($context[$property]);
                continue;
            }

            $self->{$property} = $context[$property];
        }

        return $self;
    }
}
