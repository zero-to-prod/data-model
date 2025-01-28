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
 *  'ignore' // ignores a property
 *  // Re-map a key to a property of a different name
 *  'from' => 'key',
 *  // Runs before 'cast'
 *  'pre' => [MyClass::class, 'preHook']
 *  // Targets the static method: `MyClass::methodName()`
 *  'cast' => [MyClass::class, 'castMethod'],
 *  // 'cast' => 'my_func', // alternately target a function
 *  // Runs after 'cast' passing the resolved value as `$value`
 *  'post' => [MyClass::class, 'postHook']
 *  'default' => 'value',
 *  'required', // Throws an exception if the element is missing
 *  'nullable', // sets the value to null if the element is missing
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
     *  'ignore' // ignores a property
     *  // Re-map a key to a property of a different name
     *  'from' => 'key',
     *  // Runs before 'cast'
     *  'pre' => [MyClass::class, 'preHook']
     *  // Targets the static method: `MyClass::methodName()`
     *  'cast' => [MyClass::class, 'castMethod'],
     *  // 'cast' => 'my_func', // alternately target a function
     *  // Runs after 'cast' passing the resolved value as `$value`
     *  'post' => [MyClass::class, 'postHook']
     *  'default' => 'value',
     *  'required', // Throws an exception if the element is missing
     *  'nullable', // sets the value to null if the element is missing
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
     * @param  array|object|null|string  $context  Data to populate the instance.
     * @param  mixed|null                   $instance
     */
    public static function from(array|object|null|string $context = [], mixed $instance = null): self
    {
        if ($context instanceof self) {
            return $context;
        }

        $self = $instance ?? new self();

        if (is_string($context)) {
            return $self;
        }

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

            if (isset($Describe->ignore) && $Describe->ignore) {
                continue;
            }

            $context_key = $Describe->from ?? $ReflectionProperty->getName();

            /** Property-level Pre Hook */
            if (isset($Describe->pre)) {
                ($Describe->pre)($context[$context_key] ?? [], $context, $Attribute, $ReflectionProperty);
            }

            $property_name = $ReflectionProperty->getName();

            if (isset($Describe->default) && !isset($context[$context_key])) {
                $self->{$property_name} = is_callable($Describe->default)
                    ? ($Describe->default)([], $context, $Attribute, $ReflectionProperty)
                    : $Describe->default;

                if (isset($Describe->post)) {
                    ($Describe->post)($self->{$property_name}, $context, $Attribute, $ReflectionProperty);
                }

                continue;
            }

            /** Property-level Cast */
            if (isset($Describe->cast)) {
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
            if (!isset($context[$context_key])) {
                if (isset($Describe->required) && $Describe->required) {
                    $lineNumber = static function (string $filename, string $property_name): ?int {
                        foreach (file($filename) as $line_number => $content) {
                            if (preg_match("/\\$$property_name/", $content)) {
                                return $line_number + 1;
                            }
                        }

                        // @codeCoverageIgnoreStart
                        return null;
                        // @codeCoverageIgnoreEnd
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
                if (isset($Describe->nullable) && $Describe?->nullable) {
                    $self->{$property_name} = null;
                    continue;
                }
                if (isset($ClassDescribe->nullable) && $ClassDescribe?->nullable) {
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
            if ($property_type === 'self') {
                $property_type = self::class;
            }
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
