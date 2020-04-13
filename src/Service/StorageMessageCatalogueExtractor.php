<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Service;

use Symfony\Component\Translation\MessageCatalogue;
use Translation\Common\Storage\StorageInterface;

final class StorageMessageCatalogueExtractor
{
    public function extract(StorageInterface $storage, array $locales): array
    {
        $catalogues = [];

        foreach ($locales as $locale) {
            $catalogue = new MessageCatalogue($locale);

            $storage->export($catalogue, []);

            $catalogues[] = $catalogue;
        }

        return $catalogues;
    }
}
