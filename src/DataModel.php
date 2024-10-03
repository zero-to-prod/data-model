<?php

namespace Zerotoprod\DataModel;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionUnionType;

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
        /** Get Describe Attribute on class. */
        $ClassAttribute = current(
            array_filter(
                $ReflectionClass->getAttributes(),
                static fn(ReflectionAttribute $ReflectionAttribute) => $ReflectionAttribute->getName() === Describe::class
            )
        );
        /** @var Describe|null $ClassDescribe */
        $ClassDescribe = $ClassAttribute ? $ClassAttribute->newInstance() : null;

        $methods = [];
        foreach ($ReflectionClass->getMethods() as $ReflectionMethod) {
            $ReflectionAttributes = $ReflectionMethod->getAttributes(Describe::class);
            if (!empty($ReflectionAttributes)) {
                foreach ($ReflectionAttributes as $ReflectionAttribute) {
                    $property = $ReflectionAttribute->getArguments()[0];
                    try {
                        $filename = $ReflectionClass->getMethod($methods[$property])->getFileName();
                        $start_line = $ReflectionClass->getMethod($methods[$property])->getStartLine();
                    } catch (ReflectionException) {
                        $filename = null;
                        $start_line = null;
                    }
                    $methods[$property] = isset($methods[$property])
                        ? throw new DuplicateDescribeAttributeException(
                            sprintf(
                                "\nDuplicate #[Describe($property)] attribute for property $%s found in methods:\n".
                                "%s() %s:%d\n".
                                "%s() %s:%d",
                                $property,
                                $methods[$property],
                                $filename,
                                $start_line,
                                $ReflectionMethod->getName(),
                                $ReflectionMethod->getFileName(),
                                $ReflectionMethod->getStartLine()
                            )
                        )
                        : $ReflectionMethod->getName();
                }
            }
        }

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            /** @var Describe $Describe */
            $Describe = ($ReflectionProperty->getAttributes(Describe::class)[0] ?? null)?->newInstance();
            $property_name = $ReflectionProperty->getName();

            /** Property-level Cast */
            if (isset($Describe->cast)) {
                $self->{$property_name} = ($Describe->cast)($context[$property_name], $context);
                continue;
            }

            /** Method-level Cast */
            if (isset($methods[$property_name])) {
                $self->{$property_name} = $self->{$methods[$property_name]}($context[$property_name], $context);
                continue;
            }

            /** When a property name does not match a key name  */
            if (!array_key_exists($property_name, $context)) {
                if ($Describe->required ?? false) {
                    throw new PropertyRequiredException("Property: $property_name is required");
                }
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();
            /** Assigns value when no type or union type is defined. */
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property_name} = $context[$property_name];
                continue;
            }

            $property_type = $ReflectionType->getName();
            /** Class-level cast  */
            if ($ClassDescribe?->cast[$property_type] ?? false) {
                $self->{$property_name} = $ClassDescribe->cast[$property_type]($context[$property_name], $context);
                continue;
            }

            /** Call the static method from(). */
            if (method_exists($property_type, 'from')) {
                $self->{$property_name} = $property_type::from($context[$property_name]->value ?? $context[$property_name]);
                continue;
            }

            $self->{$property_name} = $context[$property_name];
        }

        return $self;
    }
}
