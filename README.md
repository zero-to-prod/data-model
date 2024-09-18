# `Zerotoprod\DataModel`
[![Repo](https://img.shields.io/badge/github-gray?logo=github)](https://github.com/zero-to-prod/data-model)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/zero-to-prod/data-model.svg)](https://packagist.org/packages/zero-to-prod/data-model)
![test](https://github.com/zero-to-prod/data-model/actions/workflows/phpunit.yml/badge.svg)
![Downloads](https://img.shields.io/packagist/dt/zero-to-prod/data-model.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/zero-to-prod/data-model&#41)


The `Zerotoprod\DataModel` trait simplifies data handling by allowing developers to create class instances from arrays or JSON, dynamically
assigning and casting property values based on [PHP Attributes](https://www.php.net/manual/en/language.attributes.overview.php).

Whether you’re managing simple strings or complex object types, this package ensures your data models are both flexible and reliable.

Perfect for developers looking to simplify their Data Transfer Objects (DTOs);

## Features

- [Easy Instantiation](#instantiating-from-data): Create class instances from arrays or objects with automatic type casting.
- [Type Casting](#automatic-instantiation): Supports primitives, custom classes, enums, and more.
- Describe Syntax: Describe how to resolve a value before .
- Required Properties: Enforce required properties with descriptive exceptions.
- Dynamic Property Setters: Define dynamic property setters for advanced use cases.

## Installation

You can install the package via Composer:

```bash
composer require zerotoprod/data-model
```

## Usage

Import the `Zerotoprod\DataModel\DataModel` trait in your class.

It is recommended to use the `DataModel` trait in your own trait.
```php
trait DataModel
{
    use \Zerotoprod\DataModel\DataModel;
}
```
### Defining a Data Model
Include the DataModel trait in your class.
```php
readonly class User
{
    use DataModel;

    public string $name;
    public int $age;
}
```
### Instantiating from Data
Use the `from` method to instantiate your class, passing an array or object.

Notice the native type coercion.
```php
$user = User::from([
    'name' => 'John Doe',
    'age' => '30',
]);
echo $user->name; // 'John Doe'
echo $user->age; // 30
```

### Automatic Instantiation
The DataModel trait automatically casts or instantiates property values based on their type declarations. 
If a property’s type hint is a class, its value is passed to that class’s `from()` method.

In this example, the address array is automatically converted into an Address object, 
allowing direct access to its properties like `$user->address->city`.
```php
readonly class Address
{
    use DataModel;

    public string $street;
    public string $city;
}

readonly class User
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
### Handling Required Properties
Enforce that certain properties are required using the Describe attribute:
```php
use Zerotoprod\DataModel\Describe;

readonly class User
{
    use DataModel;

    #[Describe(['required' => true])]
    public string $username;

    public string $email;
}

$user = User::from(['email' => 'john@example.com']);
// Throws PropertyRequired exception: Property: username is required
```
### Custom Casting with Describe
Define custom casting logic for properties using the Describe attribute.
```php
readonly class Name
{
    use \Zerotoprod\DataModel\DataModel\DataModel;

    #[Describe('strtoupper')]
    public string $first;
}

$name = Name::from(['first' => 'john']);
echo $name->first;
```
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