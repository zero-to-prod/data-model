<?php

namespace Zerotoprod\DataModel;

use RuntimeException;

/**
 * Thrown when a duplicate Describe attribute references the same property.
 *
 * @link https://github.com/zero-to-prod/data-model
 * @see  https://github.com/zero-to-prod/data-model-helper
 * @see  https://github.com/zero-to-prod/data-model-factory
 * @see  https://github.com/zero-to-prod/transformable
 */
class DuplicateDescribeAttributeException extends RuntimeException
{

}