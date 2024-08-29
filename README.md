# Zerotoprod\DataModel

The `DataModel` trait creates class instances from arrays or JSON, dynamically assigning and casting property values based on type annotations.

## Installation

You can install the package via Composer:

```bash
composer require zerotoprod/data-model
```

## Usage

Import the `DataModel` trait in your class. Use the `from()` static method to map array keys to class properties.

It is recommended to create your own DataModel trait and import the `\Zerotoprod\DataModel\DataModel` trait as well as any additional ones.
```php
namespace App\Traits;

trait DataModel 
{
    use \Zerotoprod\DataModel\DataModel;
    use \Zerotoprod\Transformable\Transformable;
}
```

### Automatically Typecast Properties With Doc Comments

Use PHPDoc `@var` annotations to link and cast properties to their classes, ensuring accurate mapping and conversion, especially with nested objects.

```php
/** @var Address $Address */
public $Address;
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
- If a key in the array or JSON string does not correspond to a property in the class, it will be ignored.
- Unions like this are ignored: `/** @var Address|string $Address */`.
- Use the fully qualified namespace unless the class is in the same namespace: `/** @var Address $Address */`.

### Basic Example

```php
use Zerotoprod\DataModel\DataModel;

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

### Using JSON Strings

The `from()` method also accepts JSON strings.
If the JSON is valid, it will be decoded and used to populate the class properties.

```php
$user = User::from('{"name": "Jane Doe", "email": "janedoe@example.com"}');

echo $user->name; // Outputs: Jane Doe
echo $user->email; // Outputs: janedoe@example.com
```
## Suggested Traits
### Transformable
The [Transformable](https://github.com/zero-to-prod/transformable) trait provides methods to convert an object’s properties into an array or a JSON string. This is particularly useful for serializing your data models.

```bash
composer require zerotoprod/transformable
```

#### Usage
To use the `Zerotoprod\Transformable\Transformable` trait in your class, simply include it:
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