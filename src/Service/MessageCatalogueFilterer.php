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

final class MessageCatalogueFilterer
{
    public function filterByDomains(MessageCatalogue $messageCatalogue, array $whitelistedDomains, array $blacklistedDomains): MessageCatalogue
    {
        $filteredMessageCatalogue = new MessageCatalogue($messageCatalogue->getLocale());

        $translations = $messageCatalogue->all();
        foreach ($messageCatalogue->getDomains() as $domain) {
            if (!empty($blacklistedDomains) && \in_array($domain, $blacklistedDomains, true)) {
                continue;
            }

            if (!empty($whitelistedDomains) && !\in_array($domain, $whitelistedDomains, true)) {
                continue;
            }

            $filteredMessageCatalogue->add($translations[$domain], $domain);
        }

        return $filteredMessageCatalogue;
    }
}
