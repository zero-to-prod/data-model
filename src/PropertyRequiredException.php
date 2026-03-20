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
 */
class PropertyRequiredException extends RuntimeException
{

}