<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Storage;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Translation\Common\Model\Message;
use Translation\Common\Model\MessageInterface;

/**
 * An in-memory storage.
 */
final class ArrayStorage implements StorageInterface
{
    /**
     * @var MessageCatalogue[]
     */
    private $catalogues;

    /**
     * {@inheritdoc}
     */
    public function get(string $locale, string $domain, string $key): ?MessageInterface
    {
        $translation = $this->getCatalogue($locale)->get($key, $domain);

        return new Message($key, $domain, $locale, $translation);
    }

    /**
     * {@inheritdoc}
     */
    public function create(MessageInterface $message): void
    {
        $catalogue = $this->getCatalogue($message->getLocale());
        if (!$catalogue->defines($message->getKey(), $message->getDomain())) {
            $catalogue->set($message->getKey(), $message->getTranslation(), $message->getDomain());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(MessageInterface $message): void
    {
        $catalogue = $this->getCatalogue($message->getLocale());
        $catalogue->set($message->getKey(), $message->getTranslation(), $message->getDomain());
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $locale, string $domain, string $key): void
    {
        $catalogue = $this->getCatalogue($locale);
        $messages = $catalogue->all($domain);
        unset($messages[$key]);

        $catalogue->replace($messages, $domain);
    }

    /**
     * {@inheritdoc}
     */
    public function export(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        $catalogue->addCatalogue($this->getCatalogue($catalogue->getLocale()));
    }

    /**
     * {@inheritdoc}
     */
    public function import(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        $this->getCatalogue($catalogue->getLocale())->addCatalogue($catalogue);
    }

    private function getCatalogue(string $locale): MessageCatalogue
    {
        if (empty($this->catalogues[$locale])) {
            $this->catalogues[$locale] = new MessageCatalogue($locale);
        }

        return $this->catalogues[$locale];
    }
}
