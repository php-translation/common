<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Exception;

use Translation\Common\Exception;

/**
 * Storage related exceptions.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StorageException extends \RuntimeException implements Exception
{
    public static function translationExists(string $key, string $domain): self
    {
        return new self(sprintf('You cannot create a new translation with key "%s". That key does already exist in domain "%s".', $key, $domain));
    }
}
