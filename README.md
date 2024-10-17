# `Zerotoprod\DataModel`

[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/data-model)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/zero-to-prod/data-model/test.yml?label=tests)](https://github.com/zero-to-prod/data-model/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zero-to-prod/data-model?color=blue)](https://packagist.org/packages/zero-to-prod/data-model/stats)
[![Packagist Version](https://img.shields.io/packagist/v/zero-to-prod/data-model?color=f28d1a)](https://packagist.org/packages/zero-to-prod/data-model)
[![GitHub repo size](https://img.shields.io/github/repo-size/zero-to-prod/data-model)](https://github.com/zero-to-prod/data-model)
[![License](https://img.shields.io/packagist/l/zero-to-prod/data-model?color=red)](https://github.com/zero-to-prod/data-model/blob/main/LICENSE.md)

Simplify deserialization for your DTOs.

Use [PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php) to resolve
and map values to properties on a class.

Transform data into hydrated objects by [describing](#property-level-cast) how to resolve values.

## Features

- [Simple Interface](#hydrating-from-data): A single entry point to create class instances from associative arrays or objects.
- [Recursive Instantiation](#recursive-hydration): Recursively instantiate classes based on their type.
- [Type Casting](#property-level-cast): Supports primitives, custom classes, enums, and more.
- [Transformations](#transformations): Describe how to resolve a value before instantiation.
- [Required Properties](#required-properties): Throw an exception when a property is not set.

## Installation

You can install the package via Composer:

```bash
composer require zerotoprod/data-model
```

### Additional Packages

- [DataModelHelper](https://github.com/zero-to-prod/data-model-helper): Helpers for a `DataModel`.
- [DataModelFactory](https://github.com/zero-to-prod/data-model-factory): A factory helper to set the value of your `DataModel`.
- [Transformable](https://github.com/zero-to-prod/transformable): Transform a `DataModel` into different types.

## Usage

Use the `DataModel` trait in a class.

```php
class User
{
    use \Zerotoprod\DataModel\DataModel;

    public string $name;
    public int $age;
}
```

### Hydrating from Data

Use the `from` method to instantiate your class, passing an associative array or object.

```php
$user = User::from([
    'name' => 'John Doe',
    'age' => '30',
]);
echo $user->name; // 'John Doe'
echo $user->age; // 30
```

### Recursive Hydration

The `DataModel` trait recursively instantiates classes based on their type declarations.
If a property’s type hint is a class, its value is passed to that class’s `from()` method.

In this example, the `address` element is automatically converted into an `Address` object,
allowing direct access to its properties: `$user->address->city`.

```php
class Address
{
    use DataModel;

    public string $street;
    public string $city;
}

class User
{
    use DataModel;

    public string $username;
    public Address $address;
}

$user = User::from([
    'username' => 'John Doe',
    'address' => [
        'street' => '123 Main St',
        'city' => 'Hometown',
    ],
]);

echo $user->address->city; // Outputs: Hometown
```

## Transformations

The `DataModel` trait provides a variety of ways to transform data before the value is assigned to a property.

The `Describe` attribute provides a declarative way describe how property values are resolved.

### Describe Attribute

Resolve a value by adding the `Describe` attribute to a property.

The `Describe` attribute can accept these arguments.

```php
#[\Zerotoprod\DataModel\Describe([
 // Targets the static method: `MyClass::methodName()`
    'cast' => [MyClass::class, 'methodName'], 
 // alternately target a function
 // 'cast' => 'strtoupper', 
    'required' => true
])]
```

### Order of Precedence

There is an order of precedence when resolving a value for a property.

1. [Property-level Cast](#property-level-cast)
2. [Method-level Cast](#method-level-cast)
3. [Union Types](#union-types)
4. [Class-level Casts](#class-level-cast)
5. Types that have a **concrete** static method `from()`.
6. Native Types

### Property Level Cast

The using the `Describe` attribute directly on the property takes the highest precedence.

```php
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
class User
{
    use DataModel;

    #[Describe(['cast' => [self::class, 'firstName'], 'function' => 'strtoupper'])]
    public string $first_name;
    
    #[Describe(['cast' => 'uppercase'])]
    public string $last_name;

    #[Describe(['cast' => [self::class, 'fullName']])]
    public string $full_name;

    private static function firstName(mixed $value, array $context, ?\ReflectionAttribute $ReflectionAttribute, \ReflectionProperty $ReflectionProperty): string
    {
        return $ReflectionAttribute->getArguments()[0]['function']($value);
    }

    public static function fullName(null $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}

function uppercase(mixed $value, array $context){
    return strtoupper($value);
}

$user = User::from([
    'first_name' => 'Jane',
    'last_name' => 'Doe',
]);

$user->first_name;  // 'JANE'
$user->last_name;   // 'DOE'
$user->full_name;   // 'Jane Doe'
```

### Method-level Cast

Use the `Describe` attribute to resolve values with class methods. Methods receive `$value` and `$context` as parameters.

```php
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    public string $first_name;
    public string $last_name;
    public string $fullName;

    #[Describe('last_name')]
    public function lastName(?string $value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        return strtoupper($value);
    }

    #[Describe('fullName')]
    public function fullName($value, array $context, ?\ReflectionAttribute $Attribute, \ReflectionProperty $Property): string
    {
        return "{$context['first_name']} {$context['last_name']}";
    }
}

$user = User::from([
    'first_name' => 'Jane',
    'last_name' => 'Doe',
]);

$user->first_name;  // 'Jane'
$user->last_name;   // 'DOE'
$user->fullName;    // 'Jane Doe'
```

### Union Types

A value passed to property with a union type is directly assigned to the property.
If you wish to resolve the value in a specific way, use a [class method](#method-level-cast).

### Class-level Cast

You can define how to resolve different types at the class level.

```php
use DateTimeImmutable;
use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;

function uppercase(mixed $value, array $context){
    return strtoupper($value);
}

#[Describe([
    'cast' => [
        'string' => 'uppercase',
        DateTimeImmutable::class => [self::class, 'toDateTimeImmutable'],
    ]
])]
class User
{
    use DataModel;

    public string $first_name;
    public DateTimeImmutable $registered;

    public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }
}

$user = User::from([
    'first_name' => 'Jane',
    'registered' => '2015-10-04 17:24:43.000000',
]);

$user->first_name;              // 'JANE'
$user->registered->format('l'); // 'Sunday'
```

## Required Properties

Enforce that certain properties are required using the Describe attribute:

```php
use Zerotoprod\DataModel\Describe;

class User
{
    use DataModel;

    #[Describe(['required' => true])]
    public string $username;

    public string $email;
}

$user = User::from(['email' => 'john@example.com']);
// Throws PropertyRequiredException exception: Property: username is required
```