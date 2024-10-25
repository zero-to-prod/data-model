<?php

namespace Zerotoprod\DataModel;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionUnionType;
use UnitEnum;

/**
 * Enables classes to instantiate themselves from arrays or objects, auto-populating properties based on type hints and attributes.
 * Supports primitives, custom classes, enums, and allows for custom casting logic.
 *
 * Create an instance from data, populating properties based on type declarations.
 *
 * Usage
 * ```
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
 * ```
 * Property-Level
 * ```
 * #[\Zerotoprod\DataModel\Describe([
 *  // Runs before 'cast'
 *  'pre' => [MyClass::class, 'preHook']
 *  // Targets the static method: `MyClass::methodName()`
 *  'cast' => [MyClass::class, 'castMethod'],
 *  // alternately target a function
 *  // 'cast' => 'my_func',
 *  // Runs after 'cast' passing the resolved value as `$value`
 *  'post' => [MyClass::class, 'postHook']
 *  'required' => true,
 *  'default' => 'value'
 * ])]
 * public string $property;
 * ```
 * Method-Level
 * ```
 *  public string $last_name;
 *
 *  #[Describe('last_name')]
 *  public function lastName(
 *      $value,
 *      array $context,
 *      ?\ReflectionAttribute $Attribute,
 *      \ReflectionProperty $Property
 *  ): string
 *  {
 *      return strtoupper($value);
 *  }
 * ```
 * Class-Level
 * ```
 * #[Describe([
 *      'cast' => [
 *      'string' => 'uppercase',
 *      \DateTimeImmutable::class => [self::class, 'toDateTimeImmutable'],
 *  ]
 * ])]
 * class User {}
 * ```
 *
 * @link https://github.com/zero-to-prod/data-model
 * @see  https://github.com/zero-to-prod/data-model-helper
 * @see  https://github.com/zero-to-prod/data-model-factory
 * @see  https://github.com/zero-to-prod/transformable
 */
trait DataModel
{
    /**
     * Enables classes to instantiate themselves from arrays or objects, auto-populating properties based on type hints and attributes.
     * Supports primitives, custom classes, enums, and allows for custom casting logic.
     *
     * Create an instance from data, populating properties based on type declarations.
     *
     * Usage
     * ```
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
     * ```
     * Property-Level
     * ```
     * #[\Zerotoprod\DataModel\Describe([
     *  // Runs before 'cast'
     *  'pre' => [MyClass::class, 'preHook']
     *  // Targets the static method: `MyClass::methodName()`
     *  'cast' => [MyClass::class, 'castMethod'],
     *  // alternately target a function
     *  // 'cast' => 'my_func',
     *  // Runs after 'cast' passing the resolved value as `$value`
     *  'post' => [MyClass::class, 'postHook']
     *  'required' => true,
     *  'default' => 'value'
     * ])]
     * public string $property;
     * ```
     * Method-Level
     * ```
     *  public string $last_name;
     *
     *  #[Describe('last_name')]
     *  public function lastName(
     *      $value,
     *      array $context,
     *      ?\ReflectionAttribute $Attribute,
     *      \ReflectionProperty $Property
     *  ): string
     *  {
     *      return strtoupper($value);
     *  }
     * ```
     * Class-Level
     * ```
     * #[Describe([
     *      'cast' => [
     *      'string' => 'uppercase',
     *      \DateTimeImmutable::class => [self::class, 'toDateTimeImmutable'],
     *  ]
     * ])]
     * class User {}
     * ```
     *
     * @link https://github.com/zero-to-prod/data-model
     * @see  https://github.com/zero-to-prod/data-model-helper
     * @see  https://github.com/zero-to-prod/data-model-factory
     * @see  https://github.com/zero-to-prod/transformable
     *
     * @param  iterable|object|null  $context  Data to populate the instance.
     */
    public static function from(iterable|object|null $context = []): self
    {
        if ($context instanceof self) {
            return $context;
        }

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
                        if (!isset($methods[$property])) {
                            throw new ReflectionException();
                        }
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

        $context = is_object($context) ? (array)$context : $context ?? [];

        foreach ($ReflectionClass->getProperties() as $ReflectionProperty) {
            $Attribute = ($ReflectionProperty->getAttributes(Describe::class)[0] ?? null);
            /** @var Describe $Describe */
            $Describe = $Attribute?->newInstance();
            $context_key = $Describe->from ?? $ReflectionProperty->getName();

            /** Property-level Pre Hook */
            if (isset($Describe->pre)) {
                ($Describe->pre)($context[$context_key] ?? [], $context, $Attribute, $ReflectionProperty);
            }

            $property_name = $ReflectionProperty->getName();

            /** Property-level Cast */
            if (isset($Describe->cast) && $context) {
                $self->{$property_name} = ($Describe->cast)($context[$context_key] ?? [], $context, $Attribute, $ReflectionProperty);

                /** Property-level Post Hook */
                if (isset($Describe->post)) {
                    ($Describe->post)($self->{$property_name}, $context, $Attribute, $ReflectionProperty);
                }

                continue;
            }

            /** Property-level Post Hook */
            if (isset($Describe->post)) {
                $self->{$property_name} = $context[$context_key];
                ($Describe->post)($self->{$property_name}, $context, $Attribute, $ReflectionProperty);
                continue;
            }

            /** Method-level Cast */
            if (isset($methods[$property_name]) && $context) {
                $self->{$property_name} =
                    $self->{$methods[$property_name]}($context[$context_key] ?? null, $context, $Attribute, $ReflectionProperty);
                continue;
            }
            /** When a property name does not match a key name  */
            if (!array_key_exists($context_key, $context)) {
                if (isset($Describe->default)) {
                    $self->{$property_name} = $Describe->default;
                    continue;
                }
                if (isset($Describe->required) && $Describe->required) {
                    $lineNumber = static function (string $filename, string $property_name): ?int {
                        foreach (file($filename) as $line_number => $content) {
                            if (preg_match("/\\$$property_name/", $content)) {
                                return $line_number + 1;
                            }
                        }

                        return null;
                    };
                    throw new PropertyRequiredException(
                        sprintf(
                            "Property `$%s` is required.\n%s:%d",
                            $property_name,
                            $ReflectionClass->getFileName(),
                            $lineNumber($ReflectionClass->getFileName(), $property_name),
                        )
                    );
                }
                if (isset($Describe->missing_as_null) && $Describe?->missing_as_null) {
                    $self->{$property_name} = null;
                    continue;
                }
                if (isset($ClassDescribe->missing_as_null) && $ClassDescribe?->missing_as_null) {
                    $self->{$property_name} = null;
                    continue;
                }
                continue;
            }

            $ReflectionType = $ReflectionProperty->getType();
            /** Assigns value when no type or union type is defined. */
            if (!$ReflectionType || $ReflectionType instanceof ReflectionUnionType) {
                $self->{$property_name} = $context[$context_key];
                continue;
            }

            $property_type = $ReflectionType->getName();
            /** Class-level cast  */
            if ($ClassDescribe?->cast[$property_type] ?? false) {
                $self->{$property_name} =
                    $ClassDescribe?->cast[$property_type]($context[$context_key], $context, $ClassDescribeArguments);
                continue;
            }

            /** Call the static method from(). */
            if (is_callable([$property_type, 'from']) && method_exists($property_type, 'from')) {
                $self->{$property_name} = $property_type::from(
                    $context[$context_key] instanceof UnitEnum
                        ? $context[$context_key]->value
                        : $context[$context_key]
                );
                continue;
            }

            $self->{$property_name} = $context[$context_key];
        }

        return $self;
    }
}
