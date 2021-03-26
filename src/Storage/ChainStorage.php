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

use Symfony\Component\Translation\MessageCatalogueInterface;
use Translation\Common\Model\MessageInterface;

/**
 * This storage allow you to deal with several storages at once.
 */
class ChainStorage implements StorageInterface
{
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';

    private $storages = [];

    /**
     * @param StorageInterface[] $storages
     */
    public function __construct(array $storages = [])
    {
        $this->storages = $storages;
    }

    public function addStorage(StorageInterface $storage): void
    {
        $this->storages[] = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $locale, string $domain, string $key): ?MessageInterface
    {
        foreach ($this->storages as $storage) {
            if (null !== $message = $storage->get($locale, $domain, $key)) {
                return $message;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function create(MessageInterface $message): void
    {
        foreach ($this->storages as $storage) {
            $storage->create($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(MessageInterface $message): void
    {
        foreach ($this->storages as $storage) {
            $storage->update($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $locale, string $domain, string $key): void
    {
        foreach ($this->storages as $storage) {
            $storage->delete($locale, $domain, $key);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function export(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        $options['direction'] = $options['direction'] ?? self::DIRECTION_DOWN;

        $storages = $this->storages;
        if (isset($options['direction']) && self::DIRECTION_UP === $options['direction']) {
            $storages = array_reverse($storages);
        }

        foreach ($storages as $storage) {
            $storage->export($catalogue, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function import(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        foreach ($this->storages as $storage) {
            $storage->import($catalogue, $options);
        }
    }
}
