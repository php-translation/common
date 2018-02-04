<?php

declare(strict_types=1);

namespace Translation\Common\Model;


/**
 * A object representation of a translation in a specific language.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface MessageInterface
{
    /**
     * @return string
     */
    public function getDomain();

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param string $domain
     *
     * @return static
     */
    public function withDomain($domain);

    /**
     * @return string
     */
    public function getKey();

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param string $key
     *
     * @return static
     */
    public function withKey($key);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param string $locale
     *
     * @return static
     */
    public function withLocale($locale);

    /**
     * @return string
     */
    public function getTranslation();

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param string $translation
     *
     * @return static
     */
    public function withTranslation($translation);

    /**
     * @return array
     */
    public function getAllMeta();

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param array $meta
     *
     * @return static
     */
    public function withMeta(array $meta);

    /**
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @param string $key
     * @param string $value
     *
     * @return static
     */
    public function withAddedMeta($key, $value);

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getMeta($key, $default = null);
}
