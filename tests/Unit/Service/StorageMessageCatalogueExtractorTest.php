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
use Translation\Common\Model\Message;
use Translation\Common\Service\StorageMessageCatalogueExtractor;
use Translation\Common\Storage\ArrayStorage;

class StorageMessageCatalogueExtractorTest extends TestCase
{
    public function testImport()
    {
        $storage = new ArrayStorage();
        $storage->create(new Message('foo', 'domain_1', 'en', 'Foo'));
        $storage->create(new Message('bar', 'domain_2', 'en', 'Bar'));
        $storage->create(new Message('baz', 'domain_3', 'en', 'Baz'));
        $storage->create(new Message('foo', 'domain_1', 'fr', 'Foo'));
        $storage->create(new Message('foo', 'domain_1', 'nl', 'Foo'));

        $service = new StorageMessageCatalogueExtractor();
        $catalogues = $service->extract($storage, ['en', 'fr']);

        $this->assertCount(2, $catalogues);

        $this->assertSame([
            'domain_1' => ['foo' => 'Foo'],
            'domain_2' => ['bar' => 'Bar'],
            'domain_3' => ['baz' => 'Baz'],
        ], $catalogues[0]->all());

        $this->assertSame([
            'domain_1' => ['foo' => 'Foo'],
        ], $catalogues[1]->all());
    }
}
