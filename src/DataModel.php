<?php

namespace Zerotoprod\DataModel;

use ReflectionClass;
use ReflectionUnionType;
use Zerotoprod\DataModel\Helpers\Str;

/**
 * Trait DataModel
 *
 * Enables classes to instantiate themselves from arrays or objects, auto-populating properties based on type hints and attributes.
 * Supports primitives, custom classes, enums, and allows for custom casting logic.
 *
 * Example:
 * ```
 * class User
 * {
 *     use DataModel;
 *
 *     public string $name;
 *     public int $age;
 * }
 *
 * $user = User::from(['name' => 'Alice', 'age' => 30]);
 * ```
 */
trait DataModel
{
    /**
     * Create an instance from data, populating properties based on type declarations.
     *
     * Examples:
     * ```
     * class User
     * {
     *     use DataModel;
     *
     *     public string $name;
     *     public int $age;
     * }
     *
     * $user = User::from(['name' => 'Alice', 'age' => 30]);
     * ```
     *
     * @param  iterable|object|null  $context  Data to populate the instance.
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
                static fn($Attr) => $Attr->getName() === Describe::class
            )
        );
        /** @var Describe|null $ClassDescribe */
        $ClassDescribe = $ClassAttribute ? $ClassAttribute->newInstance() : null;

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            $property_name = $ReflectionProperty->getName();

            /* Invokes method matching property name. */
            if (is_callable([$self, $property_name])) {
                $self->{$property_name} = $self->{$property_name}($context[$property_name], $context);
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();
            /* Assigns value when no type or union type is defined. */
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property_name} = $context[$property_name];
                continue;
            }

            /** @var Describe $Describe */
            $Describe = ($ReflectionProperty->getAttributes(Describe::class)[0] ?? null)?->newInstance();
            if (isset($Describe->cast)) {
                $args = [$context[$property_name]];
                /* Pass the context as the second argument if not excluded. */
                if (!($Describe->exclude_context ?? false)) {
                    $args[] = $context;
                }

                $self->{$property_name} = ($Describe->cast)(...$args);
                continue;
            }

            if (!array_key_exists($property_name, $context)) {
                if ($Describe->required ?? false) {
                    throw new PropertyRequired('Property: '.$property_name.' is required');
                }
                continue;
            }

            $type = $ReflectionType->getName();

            if ($ClassDescribe?->cast[$type] ?? false) {
                $self->{$property_name} = ($ClassDescribe->cast[$type])($context[$property_name]);
                continue;
            }

            if (in_array($type, Str::types, true)) {
                $self->{$property_name} = $context[$property_name];
                continue;
            }

            if (is_callable([$type, 'from'])) {
                $self->{$property_name} = $type::from($context[$property_name]);
                continue;
            }

            $self->{$property_name} = $context[$property_name];
        }

        return $self;
    }
}
