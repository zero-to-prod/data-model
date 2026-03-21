<?php

namespace Zerotoprod\DataModel;

use Attribute;
use Closure;

use function is_bool;
use function is_string;

/**
 * PHP attribute that configures how {@see DataModel::from()} resolves a property, method, or class.
 *
 * Can be applied at three levels:
 *
 * **Property-level** — pass an associative array of configuration keys:
 * ```
 * #[Describe([
 *   'from'     => 'key',                          // Remap: read this context key instead of the property name.
 *   'pre'      => [self::class, 'hook'],           // Pre-hook: void callable, runs before cast.
 *   'cast'     => [self::class, 'method'],         // Cast: callable that returns the resolved value.
 *   'post'     => [self::class, 'hook'],           // Post-hook: void callable, runs after cast.
 *   'default'  => 'value',                         // Default: used when context key is absent. Callable OK.
 *   'assign'   => 'value',                         // Assign: always set this value; context ignored. Callable OK.
 *   'required' => true,                            // Required: throw PropertyRequiredException when key absent.
 *   'nullable' => true,                            // Nullable: set null when key absent.
 *   'ignore'   => true,                            // Ignore: skip this property entirely.
 *   'via'      => [Class::class, 'staticMethod'],  // Via: custom instantiation callable (default: 'from').
 *   'my_key'   => 'my_value',                      // Custom: unrecognized keys captured in $extra.
 * ])]
 * public string $property;
 * ```
 *
 * **Method-level** — pass the target property name as a string:
 * ```
 * #[Describe('property_name')]
 * public function resolver($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed
 * ```
 *
 * **Class-level** — map types to cast callables:
 * ```
 * #[Describe(['cast' => ['string' => 'strtoupper', DateTimeImmutable::class => [self::class, 'toDate']]])]
 * class User { use DataModel; }
 * ```
 *
 * Callable signatures (auto-detected by parameter count):
 *  - 1 param:  `function($value): mixed`
 *  - 4 params: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed`
 *
 * **Subclassing** — You can extend this class to create a project-specific attribute.
 * Subclasses are automatically recognized by {@see DataModel::from()} via `ReflectionAttribute::IS_INSTANCEOF`:
 * ```
 * #[Attribute]
 * class MyDescribe extends Describe {}
 * ```
 *
 * @link https://github.com/zero-to-prod/data-model
 */
#[Attribute]
class Describe
{
    /**
     * Deprecated alias for 'nullable'. Use `'nullable'` instead.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const missing_as_null = 'missing_as_null';

    /**
     * Key constant for {@see $from}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const from = 'from';
    /**
     * Remap: use this context key instead of the property name.
     *
     * Example: `#[Describe(['from' => 'first_name'])]` reads `$context['first_name']`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public string $from;

    /**
     * Key constant for {@see $cast}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const cast = 'cast';
    /**
     * Cast: callable that transforms the context value before assignment.
     *
     * Accepts a function name (`'strtoupper'`), a static method array (`[self::class, 'method']`),
     * a first-class callable (`self::method(...)` PHP 8.5+), or a Closure.
     *
     * Callable signatures (auto-detected by parameter count):
     *  - 1 param:  `function($value): mixed`
     *  - 4 params: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed`
     * @link https://github.com/zero-to-prod/data-model
     */
    public string|array|Closure $cast;

    /**
     * Key constant for {@see $required}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const required = 'required';
    /**
     * Required: when `true`, throws {@see PropertyRequiredException} if the context key is absent.
     *
     * Must be a boolean. Shorthand: `#[Describe(['required'])]`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $required;

    /**
     * Key constant for {@see $default}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const default = 'default';
    /**
     * Default: value used when the context key is absent. Skips cast when applied.
     *
     * When callable, invoked as `($value=null, $context, $Attribute, $Property)` and the return value is used.
     * Limitation: `null` cannot be used as a default; use `'nullable'` instead.
     * @link https://github.com/zero-to-prod/data-model
     */
    public $default;

    /**
     * Key constant for {@see $pre}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const pre = 'pre';
    /**
     * Pre-hook: void callable that runs before cast/assignment.
     *
     * Signature: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): void`
     * @link https://github.com/zero-to-prod/data-model
     */
    public $pre;

    /**
     * Key constant for {@see $post}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const post = 'post';
    /**
     * Post-hook: void callable that runs after cast/assignment.
     *
     * Signature: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): void`
     * @link https://github.com/zero-to-prod/data-model
     */
    public $post;

    /**
     * Key constant for {@see $nullable}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const nullable = 'nullable';
    /**
     * Nullable: when `true`, sets the property to `null` if the context key is absent.
     *
     * Can be set at the class level or property level. Must be a boolean.
     * Shorthand: `#[Describe(['nullable'])]`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $nullable;

    /**
     * Key constant for {@see $ignore}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const ignore = 'ignore';
    /**
     * Ignore: when `true`, the property is skipped entirely during hydration.
     *
     * Must be a boolean. Shorthand: `#[Describe(['ignore'])]`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $ignore;

    /**
     * Key constant for {@see $via}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const via = 'via';
    /**
     * Via: callable or method name used to instantiate a class-typed property.
     *
     * Defaults to `'from'`. Accepts a string method name or a callable array.
     * Example: `#[Describe(['via' => [ChildClass::class, 'create']])]`
     * @link https://github.com/zero-to-prod/data-model
     */
    public string|array $via;

    /**
     * Key constant for {@see $assign}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const assign = 'assign';

    /**
     * Key constant for {@see $extra}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const extra = 'extra';
    /**
     * Extra: stores all unrecognized keys passed to the attribute.
     *
     * Provides first-class access to custom metadata without reflection.
     * Example: `#[Describe(['cast' => [self::class, 'fn'], 'label' => 'Name'])]`
     * Access: `$Describe->extra['label']` or via `$Attribute->getArguments()[0]['label']`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public array $extra = [];
    /**
     * Assign: always set this value on the property, regardless of context.
     *
     * Unlike `default` (which only applies when the key is absent), `assign` unconditionally
     * overwrites any context value. When callable, it is invoked and the return value is assigned.
     *
     * Callable signatures (auto-detected by parameter count):
     *  - 1 param:  `function($value=null): mixed`
     *  - 4 params: `function($value=null, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed`
     *
     * Limitation: `null` cannot be used as an assigned value; use `'nullable'` instead.
     * @link https://github.com/zero-to-prod/data-model
     */
    public mixed $assign;

    /**
     * @param string|array{
     *   from?:     string,
     *   pre?:      string|array|Closure,
     *   cast?:     string|array|Closure,
     *   post?:     string|array|Closure,
     *   default?:  mixed,
     *   assign?:   mixed,
     *   required?: bool,
     *   nullable?: bool,
     *   ignore?:   bool,
     *   via?:      string|array,
     * }|null $attributes  Recognized keys configure behavior; unrecognized keys are captured in {@see $extra}.
     *                      When a string: `'required'`, `'nullable'`, or `'ignore'` set the corresponding flag to `true`.
     *                      When null or a non-array: no configuration is applied.
     *
     * @throws InvalidValue When `required`, `nullable`, `ignore`, or `missing_as_null` is not a boolean.
     * @link https://github.com/zero-to-prod/data-model
     */
    public function __construct(string|null|array $attributes = null)
    {
        if (!is_array($attributes)) {
            return;
        }

        foreach ($attributes as $key => $value) {
            switch ($key) {
                case self::required:
                    if (!is_bool($value)) {
                        throw new InvalidValue('Invalid value: `required` should be a boolean.');
                    }
                    $this->required = $value;
                    break;

                case self::nullable:
                    if (!is_bool($value)) {
                        throw new InvalidValue('Invalid value: `nullable` should be a boolean.');
                    }
                    $this->nullable = $value;
                    break;

                case self::ignore:
                    if (!is_bool($value)) {
                        throw new InvalidValue('Invalid value: `ignore` should be a boolean.');
                    }
                    $this->ignore = $value;
                    break;

                case self::missing_as_null:
                    if (!is_bool($value)) {
                        throw new InvalidValue('Invalid value: `missing_as_null` should be a boolean.');
                    }
                    $this->nullable = $value;
                    break;

                case self::from:
                    $this->from = $value;
                    break;

                case self::cast:
                    $this->cast = $value;
                    break;

                case self::default:
                    $this->default = $value;
                    break;

                case self::pre:
                    $this->pre = $value;
                    break;

                case self::post:
                    $this->post = $value;
                    break;

                case self::via:
                    $this->via = $value;
                    break;

                case self::assign:
                    $this->assign = $value;
                    break;

                case 0:
                    if (is_string($value)) {
                        switch ($value) {
                            case self::required:
                                $this->required = true;
                                break;
                            case self::missing_as_null:
                            case self::nullable:
                                $this->nullable = true;
                                break;
                            case self::ignore:
                                $this->ignore = true;
                                break;
                        }
                    }
                    break;

                default:
                    if ($value === self::missing_as_null) {
                        $this->nullable = true;
                    } else {
                        $this->extra[$key] = $value;
                    }
            }
        }
    }
}
