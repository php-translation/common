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
     * @param string $domain
     *
     * @return self
     */
    public function setDomain($domain);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     *
     * @return self
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $locale
     *
     * @return self
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getTranslation();

    /**
     * @param string $translation
     *
     * @return self
     */
    public function setTranslation($translation);

    /**
     * @return array
     */
    public function getAllMeta();

    /**
     * @param array $meta
     *
     * @return self
     */
    public function setMeta(array $meta);

    /**
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addMeta($key, $value);

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getMeta($key, $default = null);
}
