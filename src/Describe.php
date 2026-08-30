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
 *   'from'     => 'key',                          // Remap: read this context key instead of the property name. Callable OK.
 *   'pre'      => [self::class, 'hook'],           // Pre-hook: void callable, runs before cast.
 *   'cast'     => [self::class, 'method'],         // Cast: callable that returns the resolved value.
 *   'post'     => [self::class, 'hook'],           // Post-hook: void callable, runs after cast.
 *   'default'  => 'value',                         // Default: used when context key is absent. Callable OK.
 *   'assign'   => 'value',                         // Assign: always set this value; context ignored. Callable OK.
 *   'required' => true,                            // Required: throw PropertyRequiredException when key absent. Callable OK.
 *   'nullable' => true,                            // Nullable: set null when key absent.
 *   'ignore'   => true,                            // Ignore: skip this property entirely. Callable OK.
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
 * Callable signatures (auto-detected by parameter count for `cast`/`assign`; always 4 args for
 * `from`/`required`/`ignore`/`default`/`pre`/`post`):
 *  - 1 param:  `function($value): mixed`
 *  - 4 params: `function($value, array $context, ?ReflectionAttribute $Attr, ReflectionProperty $Prop): mixed`
 *
 * **PHP 8.5+** — `from`, `cast`, `required`, `default`, `pre`, `post`, `ignore`, and `assign` accept
 * a closure literal directly inside the attribute, since PHP 8.5 allows closures in constant expressions:
 * ```
 * #[Describe([
 *     Describe::default => static function (): array {
 *         return myFunction();
 *     }
 * ])]
 * public array $property;
 * ```
 * On PHP < 8.5, pass a function name, a static-method array (`[self::class, 'method']`), or a
 * first-class callable (`self::method(...)`) instead — closures remain unsupported in attribute
 * arguments on those versions.
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
     *
     * Examples:
     * ```
     * #[Describe(['missing_as_null' => true])]       // sets null when key absent
     * public ?string $a;
     *
     * #[Describe(['missing_as_null'])]               // shorthand
     * public ?string $b;
     *
     * #[Describe(['anything' => 'missing_as_null'])] // legacy: any key with this value
     * public ?string $c;
     * ```
     * @link https://github.com/zero-to-prod/data-model
     */
    public const missing_as_null = 'missing_as_null';

    /**
     * Key constant for {@see $from}.
     *
     * Examples:
     * ```
     * #[Describe(['from' => 'first_name'])]      // reads $context['first_name']
     * public string $name;
     *
     * #[Describe([Describe::from => 'meta'])]    // constant form
     * public array $data;
     * ```
     * @link https://github.com/zero-to-prod/data-model
     */
    public const from = 'from';
    /**
     * Remap: use this context key instead of the property name.
     *
     * Example: `#[Describe(['from' => 'first_name'])]` reads `$context['first_name']`.
     *
     * When callable, invoked as `($propertyName, $context, $Attribute, $Property)` and the
     * return value is used as the context key. Always called with all 4 arguments.
     * ```
     * #[Describe(['from' => static function (): string {
     *     return 'first_name';
     * }])]
     * public string $name; // PHP 8.5+: closures allowed directly in attribute arguments
     * ```
     * @link https://github.com/zero-to-prod/data-model
     */
    public string|array|Closure $from;

    /**
     * Key constant for {@see $cast}.
     *
     * Examples:
     * ```
     * #[Describe(['cast' => 'strtoupper'])]               // function name (must declare exactly 1 param)
     * public string $a;
     *
     * #[Describe(['cast' => [self::class, 'shout']])]     // static method: shout($value)
     * public string $b;
     *
     * #[Describe(['cast' => [self::class, 'withCtx']])]   // withCtx($value, $context, $Attribute, $Property)
     * public string $c;
     *
     * #[Describe(['cast' => strtoupper(...)])]            // first-class callable (PHP 8.5+ in attributes)
     * public string $d;
     * ```
     *
     * Closures cannot appear in an attribute (constant expression) — only when built directly:
     * ```
     * $Describe = new Describe(['cast' => fn($value) => strrev($value)]);
     * ($Describe->cast)('abc'); // 'cba'
     * ```
     *
     * Class-level: map a type to a callable. Non-1-param callables receive
     * `($value, $context, $ClassAttributeArguments)` — three args, not four.
     * ```
     * #[Describe(['cast' => [
     *     'string' => 'strtoupper',
     *     'int' => 'abs',
     *     DateTimeImmutable::class => [self::class, 'toDate'],
     * ]])]
     * class User { use DataModel; }
     * ```
     *
     * Note: `'trim'`/`'intval'` declare 2 parameters, so they are invoked with 4 arguments
     * and throw ArgumentCountError. Wrap them in a single-parameter method.
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
     *
     * Examples:
     * ```
     * #[Describe(['required' => true])]  // throws PropertyRequiredException when key absent
     * public string $a;
     *
     * #[Describe(['required'])]          // shorthand
     * public string $b;
     *
     * #[Describe(['required' => static function (): bool {  // PHP 8.5+
     *     return true;
     * }])]
     * public string $c;
     * ```
     *
     * Values that are neither boolean nor callable throw {@see InvalidValue}: `new Describe(['required' => 'yes'])`.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const required = 'required';
    /**
     * Required: when `true`, throws {@see PropertyRequiredException} if the context key is absent.
     *
     * Must be a boolean or a callable. Shorthand: `#[Describe(['required'])]`.
     *
     * When callable, invoked as `($value, $context, $Attribute, $Property)` — where `$value` is the
     * raw context value (or `null` when absent) — and the return value is cast to boolean.
     * Always called with all 4 arguments.
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool|string|array|Closure $required;

    /**
     * Key constant for {@see $default}.
     *
     * Examples:
     * ```
     * #[Describe(['default' => 'anon'])]                // scalar
     * public string $a;
     *
     * #[Describe(['default' => ['x']])]                 // array
     * public array $b;
     *
     * #[Describe(['default' => Suit::Hearts])]          // enum case
     * public Suit $c;
     *
     * #[Describe(['default' => [self::class, 'make']])] // callable: always 4 args, $value is null
     * public string $d;
     *
     * #[Describe(['default' => 'anon', 'post' => [self::class, 'after']])] // post still runs
     * public string $e;
     * ```
     *
     * Applies when the key is absent or its value is null. Skips {@see $cast}.
     * `null` cannot be a default — use {@see $nullable}.
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
     *
     * Example — always invoked with 4 arguments (no parameter-count detection):
     * ```
     * #[Describe(['pre' => [self::class, 'before'], 'cast' => 'strtoupper'])]
     * public string $a;
     *
     * public static function before(
     *     $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
     * ): void {
     *     // $value is the raw context value, before cast
     * }
     * ```
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
     *
     * Examples — always invoked with 4 arguments, receives the resolved value:
     * ```
     * #[Describe(['cast' => 'strtoupper', 'post' => [self::class, 'after']])]
     * public string $a;  // after() receives the cast value
     *
     * #[Describe(['post' => [self::class, 'after']])]
     * public string $b;  // no cast: value assigned as-is, then after() runs
     *
     * public static function after(
     *     $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
     * ): void {
     * }
     * ```
     *
     * Without a `cast`, the context key is read directly — pair with {@see $default},
     * {@see $nullable}, or {@see $required} when the key may be absent.
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
     *
     * Examples:
     * ```
     * #[Describe(['nullable' => true])]  // null when key absent
     * public ?string $a;
     *
     * #[Describe(['nullable'])]          // shorthand
     * public ?string $b;
     * ```
     *
     * Class level — applies to every property missing from the context
     * (property level takes precedence):
     * ```
     * #[Describe(['nullable' => true])]
     * class User { use DataModel; public ?string $a; public ?int $b; }
     * ```
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
     *
     * Examples — the property is never read from context nor written:
     * ```
     * #[Describe(['ignore' => true])]
     * public string $a;              // stays uninitialized
     *
     * #[Describe(['ignore'])]        // shorthand
     * public string $b = 'untouched'; // keeps its declared default
     *
     * #[Describe(['ignore' => static function (): bool {  // PHP 8.5+
     *     return true;
     * }])]
     * public string $c;
     * ```
     * @link https://github.com/zero-to-prod/data-model
     */
    public const ignore = 'ignore';
    /**
     * Ignore: when `true`, the property is skipped entirely during hydration.
     *
     * Must be a boolean or a callable. Shorthand: `#[Describe(['ignore'])]`.
     *
     * When callable, invoked as `($value, $context, $Attribute, $Property)` — where `$value` is the
     * raw context value (or `null` when absent) — and the return value is cast to boolean.
     * Always called with all 4 arguments.
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool|string|array|Closure $ignore;

    /**
     * Key constant for {@see $via}.
     *
     * Examples — the callable receives one argument: the context value:
     * ```
     * #[Describe(['via' => 'via'])]                   // static method on the property's type
     * public Child $a;                                // Child::via(['int' => 1])
     *
     * #[Describe(['via' => [Child::class, 'make']])]  // explicit callable
     * public Child $b;
     *
     * #[Describe(['via' => 'intval'])]                // any callable
     * public int $c;                                  // '42' => 42
     * ```
     *
     * Defaults to `'from'`. Enum values are unwrapped to `->value` before the call.
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
     *
     * Examples — always wins; the context is ignored:
     * ```
     * #[Describe(['assign' => 'fixed'])]                // scalar
     * public string $a;
     *
     * #[Describe(['assign' => true])]                   // bool
     * public bool $b;
     *
     * #[Describe(['assign' => Suit::Hearts])]           // enum case
     * public Suit $c;
     *
     * #[Describe(['assign' => [self::class, 'one']])]   // 1 param:  one($value = null)
     * public string $d;
     *
     * #[Describe(['assign' => [self::class, 'four']])]  // 4 params: four(null, $context, $Attribute, $Property)
     * public string $e;
     * ```
     *
     * `$value` is always `null`. `null` cannot be assigned — use {@see $nullable}.
     * @link https://github.com/zero-to-prod/data-model
     */
    public const assign = 'assign';

    /**
     * Key constant for {@see $extra}.
     *
     * Not an input key — a bucket for every unrecognized key:
     * ```
     * #[Describe(['cast' => [self::class, 'apply'], 'function' => 'strtoupper', 'label' => 'Name'])]
     * public string $a;
     *
     * public static function apply(
     *     $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
     * ): string {
     *     $Describe = $Attribute->newInstance();
     *     $Describe->extra['label'];                    // 'Name'
     *
     *     return ($Describe->extra['function'])($value); // 'jane' => 'JANE'
     * }
     * ```
     *
     * Raw access without instantiating: `$Attribute->getArguments()[0]['label']`.
     * Passing a literal `'extra' => [...]` key nests it inside {@see $extra} — use your own key names.
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
     *   from?:     string|array|Closure,
     *   pre?:      string|array|Closure,
     *   cast?:     string|array|Closure,
     *   post?:     string|array|Closure,
     *   default?:  mixed,
     *   assign?:   mixed,
     *   required?: bool|string|array|Closure,
     *   nullable?: bool,
     *   ignore?:   bool|string|array|Closure,
     *   via?:      string|array,
     * }|null $attributes  Recognized keys configure behavior; unrecognized keys are captured in {@see $extra}.
     *                      When a string: `'required'`, `'nullable'`, or `'ignore'` set the corresponding flag to `true`.
     *                      When null or a non-array: no configuration is applied.
     *
     * @throws InvalidValue When `required` or `ignore` is neither a boolean nor a callable, or when
     *                      `nullable`/`missing_as_null` is not a boolean.
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
                    if (!is_bool($value) && !is_callable($value)) {
                        throw new InvalidValue('Invalid value: `required` should be a boolean or a callable.');
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
                    if (!is_bool($value) && !is_callable($value)) {
                        throw new InvalidValue('Invalid value: `ignore` should be a boolean or a callable.');
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
