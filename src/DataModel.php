<?php

namespace Zerotoprod\DataModel;

use ReflectionClass;
use ReflectionException;
use Zerotoprod\DataModel\Helpers\Str;

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
     * @param  array|string|object  $value  Data to populate class properties.
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
                preg_match(Str::pattern, $ReflectionClass->getProperty($property)->getDocComment(), $matches);

                /* Cast value based on type annotation */
                switch ($matches[Str::native_type]) {
                    case Str::string:
                        $self->{$property} = !is_string($val) ? (string)$val : $val;
                        continue 2;
                    case Str::array:
                        if (is_array($val)) {
                            $self->{$property} = $val;
                            continue 2;
                        }

                        $self->{$property} = is_object($val) ? get_object_vars($val) : (array)$val;
                        continue 2;
                    case Str::int:
                        $self->{$property} = (int)$val;
                        continue 2;
                    case Str::bool:
                        $self->{$property} = (bool)$val;
                        continue 2;
                    case Str::float:
                        $self->{$property} = (float)$val;
                        continue 2;
                    case Str::object:
                        $self->{$property} = !is_object($val) ? (object)$val : $val;
                        continue 2;
                    case Str::stdClass || Str::_stdClass:
                        $self->{$property} = $val;
                        continue 2;
                }

                /* Property type references a class denoted by a leading '\' */
                if ($matches[Str::type][0] === '\\' && method_exists($matches[Str::type], Str::from)) {
                    $self->{$property} = $matches[Str::type]::from($val);

                    continue;
                }

                /* Prepend the current namespace */
                $fqns = "{$ReflectionClass->getNamespaceName()}\\{$matches[Str::type]}";
                /* Class with a 'from' method exits. */
                if (method_exists($fqns, Str::from)) {
                    $self->{$property} = $fqns::from($val);

                    continue;
                }

                $self->{$property} = $val;

                continue;
            } catch (ReflectionException $e) {
                continue;
            }
        }

        return $self;
    }
}
