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
 * readonly class User
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
 * readonly class User
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
 * readonly class User
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
 */
#[Attribute]
readonly class Describe
{
    public string|array $cast;
    public bool $required;

    /**
     *  Pass an associative array to the constructor to describe the behavior of a property when it is resolved.
     *
     *  Property example:
     *  ```
     *  use Zerotoprod\DataModel\DataModel;
     *  use Zerotoprod\DataModel\Describe;
     *
     *  readonly class User
     *  {
     *      use DataModel;
     *
     *      #[Describe(['cast' => [__CLASS__, 'firstName'], 'required' => true])]
     *      public string $first_name;
     *
     *      #[Describe(['cast' => 'uppercase'])]
     *      public string $last_name;
     *
     *      #[Describe(['cast' => [__CLASS__, 'fullName']])]
     *      public string $full_name;
     *
     *
     *      public static function firstName(mixed $value, array $context): string
     *      {
     *          return strtoupper($value);
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
     *  readonly class User
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
     *  readonly class User
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
     * @param  string|null|array{
     *      cast?: array|string,
     *      required?: bool,
     * }  $attributes
     */
    public function __construct(string|null|array $attributes = null)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}