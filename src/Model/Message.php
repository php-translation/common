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

    public function __construct(string $key, string $domain = '', string $locale = '', string $translation = '', array $meta = [])
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
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * {@inheritdoc}
     */
    public function withDomain(string $domain): MessageInterface
    {
        $new = clone $this;
        $new->domain = $domain;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function withLocale(string $locale): MessageInterface
    {
        $new = clone $this;
        $new->locale = $locale;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(): string
    {
        return $this->translation;
    }

    /**
     * {@inheritdoc}
     */
    public function withTranslation(string $translation): MessageInterface
    {
        $new = clone $this;
        $new->translation = $translation;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllMeta(): array
    {
        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function withMeta(array $meta): MessageInterface
    {
        $new = clone $this;
        $new->meta = $meta;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedMeta(string $key, $value): MessageInterface
    {
        $new = clone $this;
        $new->meta[$key] = $value;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta(string $key, $default = null)
    {
        if (\array_key_exists($key, $this->meta)) {
            return $this->meta[$key];
        }

        return $default;
    }
}
