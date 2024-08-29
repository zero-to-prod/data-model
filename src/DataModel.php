<?php

namespace Zerotoprod\DataModel;

use ReflectionClass;
use ReflectionException;
use Zerotoprod\DataModel\Helpers\Str;

trait DataModel
{
    /**
     * @param  array|string|object  $value
     */
    public static function from($value = null): self
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (!is_array($decoded)) {
                return new self();
            }

            $value = $decoded;
        }

        $self = new self();
        $ReflectionClass = new ReflectionClass(__CLASS__);

        foreach ($value as $property => $val) {
            try {
                preg_match(Str::pattern, $ReflectionClass->getProperty($property)->getDocComment(), $matches);

                /** Property has a class type annotation */
                if (isset($matches[Str::class_type])) {
                    /** Determine if the type annotation is a Fully Qualified Namespace (fqns) */
                    $fqns = $matches[Str::class_type][0] === '\\'
                        /** Class type is fully qualified */
                        ? $matches[Str::class_type]
                        /** Prepend the namespace of the current class */
                        : "{$ReflectionClass->getNamespaceName()}\\{$matches[Str::class_type]}";

                    if (method_exists($fqns, Str::from)) {
                        $self->{$property} = $fqns::from($val);
                    }

                    continue;
                }

                /** Cast value based on type annotation */
                switch ($matches[Str::type]) {
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
                        $self->{$property} = !is_int($val) ? (int)$val : $val;
                        continue 2;
                    case Str::bool:
                        $self->{$property} = !is_bool($val) ? (bool)$val : $val;
                        continue 2;
                    case Str::object:
                        $self->{$property} = !is_object($val) ? (object)$val : $val;
                        continue 2;
                    case Str::float:
                        $self->{$property} = !is_float($val) ? (float)$val : $val;
                        continue 2;
                    default:
                        $self->{$property} = $val;
                        continue 2;
                }
            } catch (ReflectionException $e) {
                continue;
            }
        }

        return $self;
    }
}
