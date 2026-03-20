<?php

namespace Zerotoprod\DataModel;

use RuntimeException;

/**
 * Thrown when two or more method-level `#[Describe('property')]` attributes target the same property name.
 *
 * Message includes both method names and their file:line locations.
 *
 * @see DataModel::from()
 * @link https://github.com/zero-to-prod/data-model
 */
class DuplicateDescribeAttributeException extends RuntimeException
{

}