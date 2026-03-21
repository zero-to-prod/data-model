<?php

namespace Zerotoprod\DataModel;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionUnionType;
use Closure;
use UnitEnum;

use function is_array;
use function is_object;
use function is_string;

/**
 *
 * Trait that adds `from()` to hydrate an object from an array or object.
 *
 * Add `use DataModel;` to any class. Call `YourClass::from($data)` to get a populated instance.
 * Properties are resolved by matching array keys to property names. Type-hinted classes
 * are recursively instantiated via their own `from()` method.
 *
 * Configure per-property behavior with the `#[Describe]` attribute (or any subclass of it).
 * See {@see Describe} for all available keys.
 *
 * Resolution order (first match wins):
 *  1. `assign`  — unconditional value (context ignored)
 *  2. `cast`    — property-level callable
 *  3. `post`    — property-level post hook (when no cast)
 *  4. Method-level `#[Describe('property')]` on a class method
 *  5. Class-level `cast` (type-based map)
 *  6. `via`     — custom instantiation callable (default: `from`)
 *  7. Direct assignment
 *
 * @link https://github.com/zero-to-prod/data-model
 * @see  Describe
 */
trait DataModel
{
    /**
     * Hydrate an instance from an array, object, or null.
     *
     * Returns `$context` unchanged when it is already an instance of the class.
     * Treats string `$context` as empty — attribute defaults (`default`, `assign`, `nullable`) still apply.
     *
     * ```
     * $user = User::from(['name' => 'Jane', 'age' => 30]);
     * ```
     *
     * Property-level attribute — all keys optional, unrecognized keys go to {@see Describe::$extra}:
     * ```
     * #[Describe([
     *   'from'     => 'key',                          // remap context key
     *   'pre'      => [self::class, 'hook'],           // void; runs before cast
     *   'cast'     => [self::class, 'method'],         // returns resolved value
     *   'post'     => [self::class, 'hook'],           // void; runs after cast
     *   'default'  => 'value',                         // used when key absent; callable OK
     *   'assign'   => 'value',                         // always set; context ignored; callable OK
     *   'required' => true,                            // throws PropertyRequiredException
     *   'nullable' => true,                            // sets null when key absent
     *   'ignore'   => true,                            // skip property entirely
     *   'via'      => [Class::class, 'staticMethod'],  // custom instantiation callable
     * ])]
     * ```
     *
     * Callable signatures (auto-detected by parameter count):
     *  - 1 param:  `function($value): mixed`
     *  - 4 params: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed`
     *
     * Method-level — tag a class method to resolve a property:
     * ```
     * #[Describe('property_name')]
     * public function resolver($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed
     * ```
     *
     * Class-level — map types to cast functions:
     * ```
     * #[Describe(['cast' => ['string' => 'strtoupper', DateTimeImmutable::class => [self::class, 'toDate']]])]
     * ```
     *
     * @param  array|object|null|string  $context   Data to populate the instance. Strings are treated as empty context.
     * @param  mixed|null                $instance  Optional pre-created instance to populate.
     *
     * @return self
     *
     * @throws PropertyRequiredException         When a required property key is missing.
     * @throws DuplicateDescribeAttributeException When two methods target the same property.
     *
     * @see Describe
     * @link https://github.com/zero-to-prod/data-model
     */
    public static function from(array|object|null|string $context = [], mixed $instance = null): self
    {
        if ($context instanceof self) {
            return $context;
        }

        $self = $instance ?? new self();

        /** Treat string context as empty so attribute defaults (default, assign, nullable) still apply. */
        if (is_string($context)) {
            $context = [];
        }

        $ReflectionClass = new ReflectionClass($self);
        /** Get Describe Attribute on class (IS_INSTANCEOF matches subclasses of Describe). */
        /** @var ReflectionAttribute|bool $ClassAttribute */
        $ClassAttribute = current($ReflectionClass->getAttributes(Describe::class, ReflectionAttribute::IS_INSTANCEOF));
        /** @var Describe|null $ClassDescribe */
        $ClassDescribe = $ClassAttribute ? $ClassAttribute->newInstance() : null;
        $ClassDescribeArguments = $ClassAttribute ? $ClassAttribute->getArguments() : null;

        $methods = [];
        foreach ($ReflectionClass->getMethods() as $ReflectionMethod) {
            $ReflectionAttributes = $ReflectionMethod->getAttributes(Describe::class, ReflectionAttribute::IS_INSTANCEOF);
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

        $propertyAttributes = [];
        $ReflectionProperties = $ReflectionClass->getProperties();
        foreach ($ReflectionProperties as $ReflectionProperty) {
            $propertyAttributes[$ReflectionProperty->getName()] =
                $ReflectionProperty->getAttributes(Describe::class, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;
        }

        $context = is_object($context) ? (array)$context : $context ?? [];

        foreach ($ReflectionProperties as $ReflectionProperty) {
            $Attribute = $propertyAttributes[$ReflectionProperty->getName()];
            $Describe = $Attribute?->newInstance();

            if (isset($Describe->ignore) && $Describe->ignore) {
                continue;
            }

            if (isset($Describe->assign)) {
                $property_name = $ReflectionProperty->getName();
                if (is_callable($Describe->assign)) {
                    $param_count = ($Describe->assign instanceof Closure
                        ? new ReflectionFunction($Describe->assign)
                        : new (is_array($Describe->assign) ? ReflectionMethod::class : ReflectionFunction::class)(...(array)$Describe->assign))
                        ->getNumberOfParameters();
                    $self->{$property_name} = $param_count === 1
                        ? ($Describe->assign)(null)
                        : ($Describe->assign)(null, $context, $Attribute, $ReflectionProperty);
                } else {
                    $self->{$property_name} = $Describe->assign;
                }
                continue;
            }

            $context_key = $Describe->from ?? $ReflectionProperty->getName();

            /** Property-level Pre Hook */
            if (isset($Describe->pre)) {
                ($Describe->pre)($context[$context_key] ?? null, $context, $Attribute, $ReflectionProperty);
            }

            $property_name = $ReflectionProperty->getName();

            if (isset($Describe->default) && !isset($context[$context_key])) {
                $self->{$property_name} = is_callable($Describe->default)
                    ? ($Describe->default)(null, $context, $Attribute, $ReflectionProperty)
                    : $Describe->default;

                if (isset($Describe->post)) {
                    ($Describe->post)($self->{$property_name}, $context, $Attribute, $ReflectionProperty);
                }

                continue;
            }

            /** Property-level Cast */
            if (isset($Describe->cast)) {
                $param_count = ($Describe->cast instanceof Closure
                    ? new ReflectionFunction($Describe->cast)
                    : new (is_array($Describe->cast) ? ReflectionMethod::class : ReflectionFunction::class)(...(array)$Describe->cast))
                    ->getNumberOfParameters();

                $self->{$property_name} = $param_count === 1
                    ? ($Describe->cast)($context[$context_key] ?? null)
                    : ($Describe->cast)($context[$context_key] ?? null, $context, $Attribute, $ReflectionProperty);

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

            $via = $Describe->via ?? 'from';
            $value = $context[$context_key] instanceof UnitEnum
                ? $context[$context_key]->value
                : $context[$context_key];

            if (is_callable($via)) {
                $self->{$property_name} = $via($value);
                continue;
            }

            if (is_callable([$property_type, $via]) && method_exists($property_type, $via)) {
                $self->{$property_name} = $property_type::$via($value);
                continue;
            }

            $self->{$property_name} = $context[$context_key];
        }

        return $self;
    }
}
