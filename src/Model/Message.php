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

final class Message implements MessageInterface
{
    /**
     * @var string
     *
     * The domain the message belongs to
     */
    private $domain;

    /**
     * @var string
     *
     * The key/phrase you write in the source code
     */
    private $key;

    /**
     * @var string
     *
     * The locale the translations is on
     */
    private $locale;

    /**
     * @var string
     *
     * The translated string. This is the preview of the message. Ie no placeholders is visible.
     */
    private $translation;

    /**
     * Key value array with metadata.
     *
     * @var array
     */
    private $meta = [];

    /**
     * @param string $key
     * @param string $domain
     * @param string $locale
     * @param string $translation
     * @param array  $meta
     */
    public function __construct($key = '', $domain = '', $locale = '', $translation = '', array $meta = [])
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->locale = $locale;
        $this->translation = $translation;
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * {@inheritdoc}
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMeta()
    {
        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMeta($key, $value)
    {
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($key, $default = null)
    {
        if (key_exists($key, $this->meta)) {
            return $this->meta[$key];
        }

        return $default;
    }
}
