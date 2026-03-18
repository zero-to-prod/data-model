<?php

namespace Zerotoprod\DataModel;

use Attribute;
use Closure;

use function is_bool;
use function is_string;

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
 *     // Or with first-class callable (PHP 8.5+):
 *     // #[Describe(['cast' => self::firstName(...), 'required' => true])]
 *     public string $first_name;
 *
 *     #[Describe(['cast' => 'uppercase'])]
 *     public string $last_name;
 *
 *     #[Describe(['cast' => [__CLASS__, 'fullName']])]
 *     // Or: #[Describe(['cast' => self::fullName(...)])]
 *     public string $full_name;
 *
 *     // Always assigns ['role' => 'admin'] regardless of what is passed in context.
 *     #[Describe(['assign' => ['role' => 'admin']])]
 *     public array $config;
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
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public const missing_as_null = 'missing_as_null';
    /**
     * @see $from
     * @link https://github.com/zero-to-prod/data-model
     */
    public const from = 'from';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public string $from;
    /**
     * @see $cast
     * @link https://github.com/zero-to-prod/data-model
     */
    public const cast = 'cast';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public string|array|Closure $cast;
    /**
     * @see $required
     * @link https://github.com/zero-to-prod/data-model
     */
    public const required = 'required';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $required;
    /**
     * @see $default
     * @link https://github.com/zero-to-prod/data-model
     */
    public const default = 'default';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public $default;
    /**
     * @see $pre
     * @link https://github.com/zero-to-prod/data-model
     */
    public const pre = 'pre';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public $pre;
    /**
     * @see $post
     * @link https://github.com/zero-to-prod/data-model
     */
    public const post = 'post';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public $post;
    /**
     * @see $nullable
     * @link https://github.com/zero-to-prod/data-model
     */
    public const nullable = 'nullable';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $nullable;
    /**
     * @see $ignore
     * @link https://github.com/zero-to-prod/data-model
     */
    public const ignore = 'ignore';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public bool $ignore;
    /**
     * @see $via
     * @link https://github.com/zero-to-prod/data-model
     */
    public const via = 'via';
    /**
     * @link https://github.com/zero-to-prod/data-model
     */
    public string|array $via;
    /**
     * @see $assign
     * @link https://github.com/zero-to-prod/data-model
     */
    public const assign = 'assign';
    /**
     * Always assigns this value to the property regardless of whether a matching key exists in the context.
     *
     * @link https://github.com/zero-to-prod/data-model
     */
    public mixed $assign;

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
     *      // Or with first-class callable (PHP 8.5+):
     *      // #[Describe(['cast' => self::firstName(...), 'function' => 'strtoupper'])]
     *      public string $first_name;
     *
     *      #[Describe(['cast' => 'uppercase'])]
     *      public string $last_name;
     *
     *      #[Describe(['cast' => [__CLASS__, 'fullName'], 'required' => true])]
     *      // Or: #[Describe(['cast' => self::fullName(...), 'required' => true])]
     *      public string $full_name;
     *
     *      // Always assigns ['role' => 'admin'] regardless of what is passed in context.
     *      #[Describe(['assign' => ['role' => 'admin']])]
     *      public array $config;
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
     * @param  string|array{'from'?: string, 'pre'?: string|string[], 'cast'?: string|array|\Closure, 'post'?: string|string[], 'required'?: bool, 'default'?: mixed, 'nullable'?: bool,
     *                                            'ignore'?: bool, 'via'?: string, 'assign'?: mixed}|null  $attributes
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
                    }
            }
        }
    }
}
