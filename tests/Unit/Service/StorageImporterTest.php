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
use Translation\Common\Service\StorageImporter;
use Translation\Common\Storage\ArrayStorage;

class StorageImporterTest extends TestCase
{
    public function testImport()
    {
        $sourceStorage = new ArrayStorage();
        $sourceStorage->create(new Message('common', 'messages', 'en', 'Translation from source storage'));
        $sourceStorage->create(new Message('foo', 'messages', 'en', 'Only in source storage'));
        $sourceStorage->create(new Message('baz', 'domain_1', 'en', 'Baz in domain 1'));
        $destinationStorage = new ArrayStorage();
        $destinationStorage->create(new Message('common', 'messages', 'en', 'Translation from destination storage'));
        $destinationStorage->create(new Message('bar', 'messages', 'en', 'Only in destination storage'));
        $destinationStorage->create(new Message('baz', 'domain_2', 'en', 'Baz in domain 2'));

        $initialSourceStorage = clone $sourceStorage;
        $expectedDestinationStorage = clone $destinationStorage;
        $expectedDestinationStorage->update(new Message('common', 'messages', 'en', 'Translation from source storage'));
        $expectedDestinationStorage->create(new Message('foo', 'messages', 'en', 'Only in source storage'));
        $expectedDestinationStorage->create(new Message('baz', 'domain_1', 'en', 'Baz in domain 1'));

        $importer = new StorageImporter();
        $importer->import($sourceStorage, $destinationStorage, ['en']);

        $this->assertEquals($initialSourceStorage, $sourceStorage);
        $this->assertEquals($expectedDestinationStorage, $destinationStorage);
    }
}
