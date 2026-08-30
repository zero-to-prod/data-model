# `Describe` Constants — Examples

Every constant on [`src/Describe.php`](src/Describe.php), with each supported form. All snippets below were executed against this package on PHP 8.5; outputs shown are actual.

Keys can be written as strings (`'cast'`) or as constants (`Describe::cast`) — identical.

> **PHP 8.5+**: `from`, `cast`, `required`, `default`, `pre`, `post`, `ignore`, and `assign` all accept a closure literal written directly inside the attribute, e.g. `#[Describe([Describe::default => static function (): array { return myFunction(); }])]`. This is possible because PHP 8.5 allows closures in constant expressions. On earlier PHP versions, use a function name, a static-method array, or a first-class callable instead.

| Const | Key | Purpose |
|---|---|---|
| [`Describe::from`](#describefrom) | `from` | Read a different context key |
| [`Describe::cast`](#describecast) | `cast` | Transform the value |
| [`Describe::required`](#describerequired) | `required` | Throw when key absent |
| [`Describe::default`](#describedefault) | `default` | Value when key absent |
| [`Describe::pre`](#describepre) | `pre` | Void hook before cast |
| [`Describe::post`](#describepost) | `post` | Void hook after cast |
| [`Describe::nullable`](#describenullable) | `nullable` | `null` when key absent |
| [`Describe::ignore`](#describeignore) | `ignore` | Skip property |
| [`Describe::via`](#describevia) | `via` | Custom instantiation callable |
| [`Describe::assign`](#describeassign) | `assign` | Unconditional value |
| [`Describe::extra`](#describeextra) | `extra` | Bucket for custom keys (read-only) |
| [`Describe::missing_as_null`](#describemissing_as_null) | `missing_as_null` | Deprecated alias for `nullable` |

---

## `Describe::from`

Reads `$context['first_name']` instead of `$context['name']`.

```php
class User
{
    use DataModel;

    #[Describe(['from' => 'first_name'])]
    public string $name;

    #[Describe([Describe::from => 'meta'])]   // const form
    public array $data;
}

User::from(['first_name' => 'Jane', 'meta' => ['a' => 1]]);
// name  => 'Jane'
// data  => ['a' => 1]
```

Also accepts a callable, always invoked with **4 arguments** — `($propertyName, $context, $Attribute, $Property)` — and returns the context key to read:

```php
#[Describe(['from' => [self::class, 'resolveKey']])]
public string $name;

public static function resolveKey(string $propertyName, array $context): string
{
    return isset($context['first_name']) ? 'first_name' : 'legacy_name';
}
```

**PHP 8.5+** — a closure literal works directly inside the attribute (closures in constant expressions):

```php
#[Describe(['from' => static function (string $propertyName, array $context): string {
    return 'first_name';
}])]
public string $name;

User::from(['first_name' => 'Jane']);
// name => 'Jane'
```

---

## `Describe::cast`

Returns the resolved value. Parameter count is auto-detected: **exactly 1** → `($value)`, anything else → `($value, $context, $Attribute, $Property)`.

### 1. Function name (string)

```php
#[Describe(['cast' => 'strtoupper'])]  public string $a;   // 'jane'  => 'JANE'
#[Describe(['cast' => 'ucfirst'])]     public string $c;   // 'jane'  => 'Jane'
```

> ⚠️ The function must declare **exactly one** parameter. `'trim'` / `'intval'` declare two (the second optional), so they are called with 4 args and throw `ArgumentCountError`. Wrap them in a static method instead.

### 2. Static method array

```php
class User
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'shout']])]
    public string $b;

    public static function shout($value): string   // 1 param
    {
        return $value.'!';
    }
}
// 'hi' => 'hi!'
```

### 3. Four-parameter callable (full context)

```php
class User
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'withContext']])]
    public string $d;

    public static function withContext(
        $value,
        array $context,
        ?ReflectionAttribute $Attribute,
        ReflectionProperty $Property
    ): string {
        return $value.'|'.$context['a'].'|'.$Property->getName();
    }
}
// User::from(['a' => 'jane', 'd' => 'z']) => 'z|jane|d'
```

### 4. First-class callable (PHP 8.5+ inside attributes)

```php
#[Describe(['cast' => strtoupper(...)])]
public string $a;                                   // 'jane' => 'JANE'
```

### 5. Closure — only when constructing `Describe` directly

Attribute arguments must be constant expressions, so an inline `fn()` cannot appear in `#[Describe(...)]`. It works when you build the object yourself (see `tests/Unit/Describe/CastClosure`):

```php
$Describe = new Describe(['cast' => fn($value) => strrev($value)]);
($Describe->cast)('abc');   // 'cba'
```

Invokable objects are rejected — `$cast` is typed `string|array|Closure`.

### 6. Closure literal directly inside the attribute (PHP 8.5+)

PHP 8.5 allows closures in constant expressions, so — unlike form 5 above — the closure can now be written straight inside `#[Describe(...)]` without building the object yourself:

```php
#[Describe(['cast' => static function ($value): string {
    return strtoupper($value);
}])]
public string $a;
// 'jane' => 'JANE'
```

On PHP < 8.5, use a function name, a static-method array, or a first-class callable instead (forms 1–4).

### 7. Class-level cast — map of type ⇒ callable

Applies to every property of that type that has no property-level resolver. Non-1-param callables receive `($value, $context, $ClassAttributeArguments)` — three args, **not** four.

```php
#[Describe([
    'cast' => [
        'string' => 'strtoupper',
        DateTimeImmutable::class => [User::class, 'toDate'],
    ]
])]
class User
{
    use DataModel;

    public string $name;
    public DateTimeImmutable $at;
    public int $age;                                  // untouched: no 'int' entry

    public static function toDate($value, array $context, ?array $arguments): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }
}

User::from(['name' => 'jane', 'at' => '2024-01-01', 'age' => 30]);
// name => 'JANE', at => 2024-01-01, age => 30
```

Single-param class-level cast:

```php
#[Describe(['cast' => ['int' => 'abs']])]
class Model { use DataModel; public int $n; }

Model::from(['n' => -5])->n;   // 5
```

---

## `Describe::required`

Throws `PropertyRequiredException` when the context key is absent. Must be a boolean or a callable.

```php
class User
{
    use DataModel;

    #[Describe(['required' => true])]
    public string $a;

    #[Describe(['required'])]      // shorthand, positional string
    public string $b;
}

User::from(['a' => 'x']);
// PropertyRequiredException: Property `$b` is required.
//   /path/User.php:9
```

Also accepts a callable, always invoked with **4 arguments** — `($value, $context, $Attribute, $Property)`, where `$value` is the raw context value (or `null` when absent) — and the return value is cast to boolean:

```php
#[Describe(['required' => [self::class, 'isRequired']])]
public string $a;

public static function isRequired($value, array $context): bool
{
    return (bool)($context['strict'] ?? false);
}
```

**PHP 8.5+** — a closure literal works directly inside the attribute:

```php
#[Describe(['required' => static function (): bool {
    return true;
}])]
public string $a;

User::from([]);
// PropertyRequiredException: Property `$a` is required.
```

Values that are neither boolean nor callable throw at attribute construction:

```php
new Describe(['required' => 'yes']);
// InvalidValue: Invalid value: `required` should be a boolean or a callable.
```

---

## `Describe::default`

Used when the context key is absent or `null`. Skips `cast`; `post` still runs.

```php
class User
{
    use DataModel;

    #[Describe(['default' => 'anon'])]                 public string $a;   // 'anon'
    #[Describe(['default' => ['x']])]                  public array $b;    // ['x']
    #[Describe(['default' => [self::class, 'make']])]  public string $c;   // 'made:c'
    #[Describe(['default' => Suit::Hearts])]           public Suit $d;     // Suit::Hearts
    #[Describe(['default' => 'ignored'])]              public string $e;   // 'present'

    public static function make(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): string {
        return 'made:'.$Property->getName();   // $value is always null here
    }
}

User::from(['e' => 'present']);
```

Callable defaults are always invoked with **4 arguments** (no param-count detection).

**PHP 8.5+** — a closure literal works directly inside the attribute (closures in constant expressions):

```php
#[Describe(['default' => static function (): array {
    return myFunction();
}])]
public array $data;

User::from([]);
// data => myFunction()'s return value
```

`default` + `post`:

```php
#[Describe(['default' => 'anon', 'post' => [self::class, 'after']])]
public string $a;
// post receives 'anon'
```

> `null` cannot be a default — use `nullable` instead.

---

## `Describe::pre`

Void hook, runs before cast/assignment. Always called with **4 arguments**, so the callable must accept them.

```php
class User
{
    use DataModel;

    #[Describe(['pre' => [self::class, 'before'], 'cast' => 'strtoupper'])]
    public string $a;

    public static function before(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): void {
        Log::add("pre:{$Property->getName()}:$value");   // 'pre:a:jane' — raw value
    }
}
```

**PHP 8.5+** — a closure literal works directly inside the attribute:

```php
#[Describe(['pre' => static function ($value): void {
    Log::add("pre:$value");
}])]
public string $a;
```

---

## `Describe::post`

Void hook, runs after the value is set. Also always called with **4 arguments**. Receives the resolved value.

```php
class User
{
    use DataModel;

    #[Describe(['pre' => [self::class, 'before'], 'post' => [self::class, 'after'], 'cast' => 'strtoupper'])]
    public string $a;

    #[Describe(['post' => [self::class, 'after']])]     // no cast: value assigned as-is, then hook
    public string $b;

    public static function after(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): void {
        Log::add("post:{$Property->getName()}:$value");
    }
}

User::from(['a' => 'jane', 'b' => 'bob']);
// pre:a:jane, post:a:JANE, post:b:bob
```

> `post` without `cast` reads `$context[$key]` directly — a missing key raises a warning/error, so pair it with `default`, `nullable`, or `required`.

**PHP 8.5+** — a closure literal works directly inside the attribute:

```php
#[Describe(['post' => static function ($value): void {
    Log::add("post:$value");
}])]
public string $a;
```

---

## `Describe::nullable`

Sets `null` when the key is absent. Must be a boolean.

```php
class User
{
    use DataModel;

    #[Describe(['nullable' => true])]  public ?string $a;   // null
    #[Describe(['nullable'])]          public ?string $b;   // null — shorthand
}

User::from([]);
```

Class level — applies to every property missing from the context:

```php
#[Describe(['nullable' => true])]
class User
{
    use DataModel;

    public ?string $a;   // null
    public ?int $b;      // null
}
```

Property level wins over class level.

---

## `Describe::ignore`

Skips the property entirely — never read, never written. Must be a boolean or a callable.

```php
class User
{
    use DataModel;

    public string $a;                                    // 'x'

    #[Describe(['ignore' => true])]
    public string $b;                                    // uninitialized

    #[Describe(['ignore'])]                              // shorthand
    public string $c = 'untouched';                      // 'untouched'
}

User::from(['a' => 'x', 'b' => 'y', 'c' => 'z']);
```

Also accepts a callable, always invoked with **4 arguments** — `($value, $context, $Attribute, $Property)` — and the return value is cast to boolean:

```php
#[Describe(['ignore' => [self::class, 'shouldIgnore']])]
public string $b = 'untouched';

public static function shouldIgnore($value, array $context): bool
{
    return (bool)($context['skip'] ?? false);
}
```

**PHP 8.5+** — a closure literal works directly inside the attribute:

```php
#[Describe(['ignore' => static function (): bool {
    return true;
}])]
public string $b = 'untouched';

User::from(['b' => 'y']);
// b => 'untouched'
```

---

## `Describe::via`

Custom instantiation for a class-typed property. Defaults to `'from'`.

```php
class Child
{
    public function __construct(public int $int) {}

    public static function via(array $context): self  { return new self($context['int']); }
    public static function make(array $context): self { return new self($context['int'] * 2); }
}

class User
{
    use DataModel;

    #[Describe(['via' => 'via'])]                   // static method name on the property's type
    public Child $a;

    #[Describe(['via' => [Child::class, 'make']])]  // explicit callable
    public Child $b;

    #[Describe(['via' => 'intval'])]                // any callable — receives the raw value
    public int $c;
}

User::from(['a' => ['int' => 1], 'b' => ['int' => 1], 'c' => '42']);
// a->int => 1, b->int => 2, c => 42
```

The callable receives one argument: the context value (enum values are unwrapped to `->value` first).

---

## `Describe::assign`

Unconditional — wins over everything, context ignored. Param count detected: **exactly 1** → `(null)`, otherwise `(null, $context, $Attribute, $Property)`. `$value` is always `null`.

```php
class User
{
    use DataModel;

    #[Describe(['assign' => 'fixed'])]                public string $a;   // 'fixed' (context 'from-context' ignored)
    #[Describe(['assign' => [self::class, 'one']])]   public string $b;   // 'one-param'
    #[Describe(['assign' => [self::class, 'four']])]  public string $c;   // 'ctx:y:c'
    #[Describe(['assign' => Suit::Hearts])]           public Suit $d;     // Suit::Hearts
    #[Describe(['assign' => true])]                   public bool $e;     // true

    public static function one($value = null): string
    {
        return 'one-param';
    }

    public static function four(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): string {
        return 'ctx:'.($context['x'] ?? 'none').':'.$Property->getName();
    }
}

User::from(['a' => 'from-context', 'x' => 'y']);
```

> `null` cannot be assigned — use `nullable` instead.

**PHP 8.5+** — a closure literal works directly inside the attribute:

```php
#[Describe(['assign' => static function (): string {
    return 'fixed';
}])]
public string $a;

User::from(['a' => 'from-context']);
// a => 'fixed'
```

---

## `Describe::extra`

Not an input key — a **bucket**. Every unrecognized key lands in `$Describe->extra`, giving typed access to custom metadata.

```php
class User
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'apply'], 'function' => 'strtoupper', 'label' => 'Name'])]
    public string $a;

    public static function apply(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): string {
        $Describe = $Attribute->newInstance();

        $Describe->extra['label'];               // 'Name'
        return ($Describe->extra['function'])($value);
    }
}

User::from(['a' => 'jane'])->a;   // 'JANE'
```

Raw access without instantiating: `$Attribute->getArguments()[0]['function']`.

Direct:

```php
$Describe = new Describe(['cast' => 'strtoupper', 'label' => 'Name', 'rules' => ['required']]);
$Describe->extra;   // ['label' => 'Name', 'rules' => ['required']]
$Describe->cast;    // 'strtoupper' — recognized keys never enter extra
```

> Passing a literal `'extra' => [...]` key is itself unrecognized, so it lands inside `extra` as `['extra' => [...]]`. Use your own key names.

---

## `Describe::missing_as_null`

Deprecated alias for `nullable` — sets `$nullable`. Kept for BC; prefer `nullable`.

```php
class User
{
    use DataModel;

    #[Describe(['missing_as_null' => true])]        public ?string $a;   // null
    #[Describe(['missing_as_null'])]                public ?string $b;   // null — shorthand
    #[Describe(['anything' => 'missing_as_null'])]  public ?string $c;   // null — legacy value form
}

User::from([]);
```

The third form is a legacy quirk: **any** key whose *value* is the string `'missing_as_null'` sets `nullable = true`.

---

## Bonus: forms that are not keys

**Method-level** — a string argument targets a property; the method resolves it (4 args):

```php
class User
{
    use DataModel;

    public string $a;

    #[Describe('a')]
    public function resolveA(
        $value, array $context, ?ReflectionAttribute $Attribute, ReflectionProperty $Property
    ): string {
        return strtoupper($value).':'.$Property->getName();   // 'JANE:a'
    }
}
```

Two methods targeting the same property throw `DuplicateDescribeAttributeException`.

**Subclassing** — recognized via `ReflectionAttribute::IS_INSTANCEOF`, all keys behave identically:

```php
#[Attribute]
class MyDescribe extends Describe {}

class User
{
    use DataModel;

    #[MyDescribe(['cast' => 'strtoupper'])]
    public string $a;   // 'JANE'
}
```
