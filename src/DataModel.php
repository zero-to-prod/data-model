<?php

namespace Zerotoprod\DataModel;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionUnionType;
use UnitEnum;

/**
 * Trait DataModel
 *
 * Enables classes to instantiate themselves from arrays or objects, auto-populating properties based on type hints and attributes.
 * Supports primitives, custom classes, enums, and allows for custom casting logic.
 *
 * ```
 * // Usage
 * $user = User::from([
 *     'first_name' => 'Jane',
 *     'last_name' => 'Doe',
 *     'registered' => '2015-10-04 17:24:43.000000'
 * ]);
 *
 * $user->first_name;              // 'Jane'
 * $user->last_name:               // 'DOE'
 * $user->full_name:               // 'Jane Doe'
 * $user->registered->format('l'); // 'Sunday'
 *
 * // Implementation
 * #[Describe([
 *  'cast' => [
 *      DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
 *  ]
 * ])]
 * class User
 * {
 *     use DataModel;
 *
 *     public string $first_name;
 *
 *     public string $last_name;
 *
 *     #[Describe(['cast' => [__CLASS__, 'fullName']])]
 *     public string $full_name;
 *
 *     public DateTimeImmutable $registered;
 *
 *     #[Describe('last_name')]
 *     public function lastName(?string $value, array $context): string
 *     {
 *         return strtoupper($value);
 *     }
 *
 *     public static function fullName(null $value, array $context): string
 *     {
 *         return "{$context['first_name']} {$context['last_name']}";
 *     }
 *
 *     public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
 *     {
 *         return new DateTimeImmutable($value);
 *     }
 * }
 * ```
 */
trait DataModel
{
    /**
     * Create an instance from data, populating properties based on type declarations.
     * ```
     * // Usage
     * $user = User::from([
     *     'first_name' => 'Jane',
     *     'last_name' => 'Doe',
     *     'registered' => '2015-10-04 17:24:43.000000'
     * ]);
     *
     * $user->first_name;              // 'Jane'
     * $user->last_name:               // 'DOE'
     * $user->full_name:               // 'Jane Doe'
     * $user->registered->format('l'); // 'Sunday'
     *
     * // Implementation
     * #[Describe([
     *  'cast' => [
     *      DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
     *  ]
     * ])]
     * class User
     * {
     *     use DataModel;
     *
     *     public string $first_name;
     *
     *     public string $last_name;
     *
     *     #[Describe(['cast' => [__CLASS__, 'fullName']])]
     *     public string $full_name;
     *
     *     public DateTimeImmutable $registered;
     *
     *     #[Describe('last_name')]
     *     public function lastName(?string $value, array $context): string
     *     {
     *         return strtoupper($value);
     *     }
     *
     *     public static function fullName(null $value, array $context): string
     *     {
     *         return "{$context['first_name']} {$context['last_name']}";
     *     }
     *
     *     public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
     *     {
     *         return new DateTimeImmutable($value);
     *     }
     * }
     * ```
     *
     * @link https://github.com/zero-to-prod/data-model
     * @see  https://github.com/zero-to-prod/data-model-helper
     * @see  https://github.com/zero-to-prod/data-model-factory
     * @see  https://github.com/zero-to-prod/transformable
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
        /** @var ReflectionAttribute $ClassAttribute */
        $ClassAttribute = current(
            array_filter(
                $ReflectionClass->getAttributes(),
                static fn(ReflectionAttribute $ReflectionAttribute) => $ReflectionAttribute->getName() === Describe::class
            )
        );
        /** @var Describe|null $ClassDescribe */
        $ClassDescribe = $ClassAttribute ? $ClassAttribute->newInstance() : null;
        $ClassDescribeArguments = $ClassAttribute ? $ClassAttribute->getArguments() : null;

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
            $Attribute = ($ReflectionProperty->getAttributes(Describe::class)[0] ?? null);
            /** @var Describe $Describe */
            $Describe = $Attribute?->newInstance();
            $property_name = $ReflectionProperty->getName();

            /** Property-level Cast */
            if (isset($Describe->cast)) {
                $self->{$property_name} = ($Describe->cast)($context[$property_name] ?? null, $context, $Attribute, $ReflectionProperty);
                continue;
            }

            /** Method-level Cast */
            if (isset($methods[$property_name])) {
                $self->{$property_name} =
                    $self->{$methods[$property_name]}($context[$property_name] ?? null, $context, $Attribute, $ReflectionProperty);
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
                $self->{$property_name} =
                    $ClassDescribe?->cast[$property_type]($context[$property_name], $context, $ClassDescribeArguments);
                continue;
            }

            /** Call the static method from(). */
            if (is_callable([$property_type, 'from']) && method_exists($property_type, 'from')) {
                $self->{$property_name} = $property_type::from(
                    $context[$property_name] instanceof UnitEnum
                        ? $context[$property_name]->value
                        : $context[$property_name]
                );
                continue;
            }

            $self->{$property_name} = $context[$property_name];
        }

        return $self;
    }
}
