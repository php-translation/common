<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageCatalogue;
use Translation\Common\Service\MessageCatalogueFilterer;

class MessageCatalogueFiltererTest extends TestCase
{
    public function testFilterWithoutWhitelistNorBlacklist()
    {
        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('foo', 'foo', 'domain_1');
        $messageCatalogue->set('bar', 'bar', 'domain_2');

        $service = new MessageCatalogueFilterer();
        $filteredMessageCatalogue = $service->filterByDomains($messageCatalogue, [], []);

        $this->assertEquals($messageCatalogue, $filteredMessageCatalogue);
    }

    public function testFilterWithWhitelistOnly()
    {
        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('foo', 'foo', 'domain_1');
        $messageCatalogue->set('bar', 'bar', 'domain_2');

        $expectedMessageCatalogue = new MessageCatalogue('en');
        $expectedMessageCatalogue->set('foo', 'foo', 'domain_1');

        $service = new MessageCatalogueFilterer();
        $filteredMessageCatalogue = $service->filterByDomains($messageCatalogue, ['domain_1'], []);

        $this->assertEquals($expectedMessageCatalogue, $filteredMessageCatalogue);
    }

    public function testFilterWithBlacklistOnly()
    {
        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('foo', 'foo', 'domain_1');
        $messageCatalogue->set('bar', 'bar', 'domain_2');

        $expectedMessageCatalogue = new MessageCatalogue('en');
        $expectedMessageCatalogue->set('foo', 'foo', 'domain_1');

        $service = new MessageCatalogueFilterer();
        $filteredMessageCatalogue = $service->filterByDomains($messageCatalogue, [], ['domain_2']);

        $this->assertEquals($expectedMessageCatalogue, $filteredMessageCatalogue);
    }
}
