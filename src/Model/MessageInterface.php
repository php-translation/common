<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Model;

/**
 * A object representation of a translation in a specific language.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface MessageInterface
{
    public function getDomain(): string;

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     */
    public function withDomain(string $domain): self;

    public function getKey(): string;

    public function getLocale(): string;

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     */
    public function withLocale(string $locale): self;

    public function getTranslation(): string;

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     */
    public function withTranslation(string $translation): self;

    public function getAllMeta(): array;

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     */
    public function withMeta(array $meta): self;

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     */
    public function withAddedMeta(string $key, string $value): self;

    /**
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getMeta(string $key, $default = null);
}
