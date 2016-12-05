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

/**
 * The storage is a place when you can store your translations. A database, filesystem or a third party platform.
 */
interface Storage
{
    /**
     * Get a translation
     *
     * @param string $locale
     * @param string $domain
     * @param string $key
     *
     * @return string
     */
    public function get($locale, $domain, $key);

    /**
     * Update a translation
     *
     * @param string $locale
     * @param string $domain
     * @param string $key
     * @param string $message
     */
    public function update($locale, $domain, $key, $message);

    /**
     * Remove a translation from the storage.
     *
     * @param string $locale
     * @param string $domain
     * @param string $key
     */
    public function delete($locale, $domain, $key);

}

