<?php

namespace Translation\Common\Exception;

use Translation\Common\Exception;

/**
 * Storage related exceptions.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StorageException extends \RuntimeException implements Exception
{
    public static function translationExists($key, $domain)
    {
        return new self(sprintf('You cannot create a new translation with key "%s". That key does already exist in domain "%s".', $key, $domain));
    }
}
