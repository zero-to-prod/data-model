<?php

namespace Zerotoprod\DataModel;

use ReflectionClass;
use ReflectionException;

/**
 * The `DataModel` trait creates class instances from arrays, strings, or objects,
 * automatically casting types based on PHPDoc annotations. It simplifies populating
 * class properties by using reflection to match data with annotated types.
 *
 * @package Zerotoprod\DataModel
 */
trait DataModel
{
    /**
     * Instantiates the class from an array, string, or object, casting values based on PHPDoc annotations.
     * Uses reflection to match data with property types, supporting primitives and classes with a `from` method.
     *
     * Example:
     * ```
     * MyClass::from(['name' => 'John Doe']);
     * MyClass::from($stdClass);
     * ```
     *
     * @param  iterable|object|null  $value  Data to populate class properties.
     */
    public static function from($value = null): self
    {
        if ($value instanceof self) {
            return $value;
        }

        $self = new self();
        $ReflectionClass = new ReflectionClass(__CLASS__);

        foreach ($value as $property => $val) {
            try {
                $child_classname = class_exists($ReflectionClass->getProperty($property)->name)
                    ? $ReflectionClass->getProperty($property)->name
                    : $ReflectionClass->getNamespaceName()."\\".$ReflectionClass->getProperty($property)->name;

                if (method_exists($child_classname, 'from')) {
                    $self->{$property} = $child_classname::from($val);
                    continue;
                }

                $self->{$property} = $val;

                continue;
            } catch (ReflectionException) {
                continue;
            }
        }

        return $self;
    }
}
