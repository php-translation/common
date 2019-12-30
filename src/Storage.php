<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common;

use Translation\Common\Model\MessageInterface;

/**
 * The storage is a place when you can store your translations. A database, filesystem
 * or a third party platform.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface Storage
{
    /**
     * Get a translation. If no translation is found, null MUST be returned.
     */
    public function get(string $locale, string $domain, string $key): ?MessageInterface;

    /**
     * Create a new translation or asset. If a translation already exist this function
     * will do nothing.
     */
    public function create(MessageInterface $message): void;

    /**
     * Update a translation. Creates a translation if there is none to update.
     */
    public function update(MessageInterface $message): void;

    /**
     * Remove a translation from the storage. If the storage implementation makes
     * a difference between translations and assets then this function MUST only
     * remove the translation.
     */
    public function delete(string $locale, string $domain, string $key): void;
}
