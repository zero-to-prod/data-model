<?php

namespace Zerotoprod\DataModel;

use RuntimeException;

/**
 * Thrown when a Describe attribute key receives an invalid value type.
 *
 * For example, passing a non-boolean to `required`, `nullable`, `ignore`, or `missing_as_null`.
 *
 * @see Describe::__construct()
 */
class InvalidValue extends RuntimeException
{

}