<?php

namespace Zerotoprod\DataModel;

use RuntimeException;

/**
 * Thrown when a property marked with `#[Describe(['required' => true])]` has no matching key in the context.
 *
 * Message format: `"Property `$name` is required.\n/path/to/File.php:lineNumber"`
 *
 * @see Describe::$required
 * @see DataModel::from()
 * @link https://github.com/zero-to-prod/data-model
 */
class PropertyRequiredException extends RuntimeException
{

}