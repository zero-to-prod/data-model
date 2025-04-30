<?php

namespace Zerotoprod\DataModel;

use Attribute;

/**
 * Pass an associative array to the constructor to describe the behavior of a property when it is resolved.
 *
 * Property example:
 * ```
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * class User
 * {
 *     use DataModel;
 *
 *     #[Describe(['cast' => [__CLASS__, 'firstName'], 'required' => true])]
 *     public string $first_name;
 *
 *     #[Describe(['cast' => 'uppercase'])]
 *     public string $last_name;
 *
 *     #[Describe(['cast' => [__CLASS__, 'fullName']])]
 *     public string $full_name;
 *
 *
 *     public static function firstName(mixed $value, array $context): string
 *     {
 *         return strtoupper($value);
 *     }
 *
 *     public static function fullName(null $value, array $context): string
 *     {
 *         return "{$context['first_name']} {$context['last_name']}";
 *     }
 * }
 * ```
 * Method example:
 * ```
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * class User
 * {
 *     use DataModel;
 *
 *     public string $first_name;
 *     public string $last_name;
 *     public string $fullName;
 *
 *     #[Describe('last_name')]
 *     public function lastName(?string $value, array $context): string
 *     {
 *         return strtoupper($value);
 *     }
 *
 *     #[Describe('fullName')]
 *     public function fullName(null $value, array $context): string
 *     {
 *         return "{$context['first_name']} {$context['last_name']}";
 *     }
 * }
 * ```
 * Class example:
 * ```
 * use DateTimeImmutable;
 * use Zerotoprod\DataModel\DataModel;
 * use Zerotoprod\DataModel\Describe;
 *
 * function uppercase(mixed $value, array $context){
 *      return strtoupper($value);
 * }
 *
 * #[Describe([
 *  'cast' => [
 *      'string' => 'uppercase',
 *      DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
 *  ]
 * ])]
 * class User
 * {
 *     use DataModel;
 *
 *     public string $first_name;
 *     public DateTimeImmutable $registered;
 *
 *     public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
 *     {
 *         return new DateTimeImmutable($value);
 *     }
 * }
 * ```
 *
 * @link https://github.com/zero-to-prod/data-model
 *
 * @see  https://github.com/zero-to-prod/data-model-helper
 * @see  https://github.com/zero-to-prod/data-model-factory
 * @see  https://github.com/zero-to-prod/transformable
 */
#[Attribute]
class Describe
{
    public const missing_as_null = 'missing_as_null';
    /** @see $from */
    public const from = 'from';
    public string $from;
    /** @see $cast */
    public const cast = 'cast';
    public string|array $cast;
    /** @see $required */
    public const required = 'required';
    public bool $required;
    /** @see $default */
    public const default = 'default';
    public $default;
    /** @see $pre */
    public const pre = 'pre';
    public $pre;
    /** @see $post */
    public const post = 'post';
    public $post;
    /** @see $nullable */
    public const nullable = 'nullable';
    public bool $nullable;
    /** @see $ignore */
    public const ignore = 'ignore';
    public bool $ignore;
    /** @see $via */
    public const via = 'via';
    public string|array $via;

    /**
     *  Pass an associative array to the constructor to describe the behavior of a property when it is resolved.
     *
     *  Property example:
     *  ```
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  class User
     *  {
     *      use DataModel;
     *
     *      #[Describe(['cast' => [__CLASS__, 'firstName'], 'function' => 'strtoupper'])]
     *      public string $first_name;
     *
     *      #[Describe(['cast' => 'uppercase'])]
     *      public string $last_name;
     *
     *      #[Describe(['cast' => [__CLASS__, 'fullName'], 'required' => true])]
     *      public string $full_name;
     *
     *      private static function firstName(mixed $value, array $context, ?\ReflectionAttribute $ReflectionAttribute, \ReflectionProperty $ReflectionProperty): string
     *      {
     *          return $ReflectionAttribute->getArguments()[0]['function']($value);
     *      }
     *
     *      public static function fullName(null $value, array $context): string
     *      {
     *          return "{$context['first_name']} {$context['last_name']}";
     *      }
     *  }
     *  ```
     *  Method example:
     *  ```
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  class User
     *  {
     *      use DataModel;
     *
     *      public string $first_name;
     *      public string $last_name;
     *      public string $fullName;
     *
     *      #[Describe('last_name')]
     *      public function lastName(?string $value, array $context): string
     *      {
     *          return strtoupper($value);
     *      }
     *
     *      #[Describe('fullName')]
     *      public function fullName(null $value, array $context): string
     *      {
     *          return "{$context['first_name']} {$context['last_name']}";
     *      }
     *  }
     *  ```
     *  Class example:
     *  ```
     *  use DateTimeImmutable;
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  function uppercase(mixed $value, array $context){
     *       return strtoupper($value);
     *  }
     *
     *  #[Describe([
     *   'cast' => [
     *       'string' => 'uppercase',
     *       DateTimeImmutable::class => [__CLASS__, 'toDateTimeImmutable'],
     *   ]
     *  ])]
     *  class User
     *  {
     *      use DataModel;
     *
     *      public string $first_name;
     *      public DateTimeImmutable $registered;
     *
     *      public static function toDateTimeImmutable(string $value, array $context): DateTimeImmutable
     *      {
     *          return new DateTimeImmutable($value);
     *      }
     *  }
     *  ```
     *
     * @param  string|array{'from': string,'pre': string|string[], 'cast': string|string[], 'post': string|string[], 'required': bool, 'default': mixed, 'nullable': bool,
     *                                            'ignore': bool, 'via': string}|null  $attributes
     *
     * @link https://github.com/zero-to-prod/data-model
     *
     * @see  https://github.com/zero-to-prod/data-model-helper
     * @see  https://github.com/zero-to-prod/data-model-factory
     * @see  https://github.com/zero-to-prod/transformable
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
                    }
            }
        }
    }
}