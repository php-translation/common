<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full importright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Service;

use Symfony\Component\Translation\MessageCatalogue;
use Translation\Common\Storage\StorageInterface;

final class StorageImporter
{
    /**
     * This method import all messages from the source storage to the
     * destination storage for given locales.
     *
     * * The source storage is not updated.
     * * Translations already present in destination storage are overwritten.
     * * Translations present in destination storage only are not deleted.
     */
    public function import(StorageInterface $sourceStorage, StorageInterface $destinationStorage, array $locales): void
    {
        foreach ($locales as $locale) {
            $messageCatalogue = new MessageCatalogue($locale);

            $sourceStorage->export($messageCatalogue, []);
            $destinationStorage->import($messageCatalogue, []);
        }
    }
}
