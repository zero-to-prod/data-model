# `Zerotoprod\DataModel`
[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/data-model)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/zero-to-prod/data-model.svg)](https://packagist.org/packages/zero-to-prod/data-model)
![test](https://github.com/zero-to-prod/data-model/actions/workflows/phpunit.yml/badge.svg)
![Downloads](https://img.shields.io/packagist/dt/zero-to-prod/data-model.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/zero-to-prod/data-model&#41)


The `Zerotoprod\DataModel` trait simplifies data handling by allowing developers to create class instances from arrays or JSON, dynamically
assigning and casting property values based on type annotations.

Whether you’re managing simple strings or complex object types, this package ensures your data models are both flexible and reliable.

Perfect for developers looking to simplify their Data Transfer Objects (DTOs);

## Installation

You can install the package via Composer:

```bash
composer require zerotoprod/data-model
```

## Usage

Import the `Zerotoprod\DataModel\DataModel` trait in your class. Use the `from()` static method to map array keys to class properties.

It is recommended to extend your own `DataModel` trait with the `Zerotoprod\DataModel\DataModel` trait.
### Basic Example

```php
trait DataModel extends \Zerotoprod\DataModel\DataModel
{
    // Add additional utility methods and traits
}

class User
{
    use DataModel;

    public $name;
    public $email;
    /** @var \App\Address $Address */
    public Address;
}

namespace App;

class Address
{
    use DataModel;

    public $street;
}

$user = User::from(
    [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'Address' => ['street' => '456 Memory Lane']
    ]
);

echo $user->name; // 'John Doe'
echo $user->email; // 'johndoe@example.com'
echo $user->Address->street; // '456 Memory Lane'
```

### Automatically Typecast Properties With Doc Comments

Use PHPDoc `@var` annotations to link and cast properties to their classes, ensuring accurate mapping and conversion, especially with nested objects.

```php
/** @var Address $Address */
public $Address;
```
Inlining the fully qualified namespace is faster as a lookup does not have to be performed.
```php
/** @var \App\Address $Address */
```

This directs the `from()` method to instantiate the `Address` class for `$Address` when mapping data.

Automatically casted types include:

- `string`
- `array`
- `int`
- `bool`
- `object`
- `float`
- Classes with a `from()` method

**Notes**

- The `from()` method will only assign values to existing properties in the class.
- If a key does not correspond to a property in the class, it will be ignored.
- Unions like this are ignored: `/** @var Address|string $Address */`.
- Use the fully qualified namespace unless the class is in the same namespace: `/** @var Address $Address */`.

## Suggested Traits

### `Zerotoprod\DataModel\FromJson`

This will decode a valid json string and return the data model.

#### Usage

To use the `Zerotoprod\DataModel\FromJson` trait in your class.

```php

class User
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\DataModel\FromJson;

    public $name;
    public $email;
}

$user = User::fromJson('"name":"John", "email":"john@domain.com"');
$user->name;  // 'John'
$user->email; // 'john@domain.com'
```

### `Zerotoprod\Transformable`

The [Transformable](https://github.com/zero-to-prod/transformable) trait provides methods to convert an object’s properties into an array or a JSON
string. This is particularly useful for serializing your data models.

```bash
composer require zerotoprod/transformable
```

#### Usage

To use the `Zerotoprod\Transformable\Transformable` trait in your class.

```php

class YourDataModel
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\Transformable\Transformable;

    public $name;
    public $email;
}

$model = new YourDataModel();

$array = $model->toArray();
$json = $model->toJson();
```