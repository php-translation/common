<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\common\tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Translation\Common\Model\Message;
use Translation\Common\Storage\ChainStorage;
use Translation\Common\Storage\StorageInterface;

class ChainStorageTest extends TestCase
{
    private $childStorage1;
    private $childStorage2;
    private $storage;

    protected function setUp(): void
    {
        $this->childStorage1 = $this->prophesize(StorageInterface::class);
        $this->childStorage2 = $this->prophesize(StorageInterface::class);

        $this->storage = new ChainStorage();
        $this->storage->addStorage($this->childStorage1->reveal());
        $this->storage->addStorage($this->childStorage2->reveal());
    }

    public function testGetWithMessageInFirstStorage()
    {
        $expectedMessage = new Message('PHP Translation IS awesome!');

        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1)->willReturn($expectedMessage);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldNotBeCalled();

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertSame($expectedMessage, $message);
    }

    public function testGetWithMessageInSecondStorage()
    {
        $expectedMessage = new Message('PHP Translation IS awesome!');

        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1)->willReturn(null);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1)->willReturn($expectedMessage);

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertSame($expectedMessage, $message);
    }

    public function testGetWithMessageNotFound()
    {
        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1)->willReturn(null);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1)->willReturn(null);

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertNull($message);
    }

    public function testCreateCallAllStorages()
    {
        $message = new Message('PHP Translation IS awesome!');

        $this->childStorage1->create($message)->shouldBeCalledtimes(1);
        $this->childStorage2->create($message)->shouldBeCalledtimes(1);

        $this->storage->create($message);
    }

    public function testUpdateCallAllStorages()
    {
        $message = new Message('PHP Translation IS awesome!');

        $this->childStorage1->update($message)->shouldBeCalledtimes(1);
        $this->childStorage2->update($message)->shouldBeCalledtimes(1);

        $this->storage->update($message);
    }

    public function testDeleteCallAllStorages()
    {
        $this->childStorage1->delete('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1);
        $this->childStorage2->delete('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledtimes(1);

        $this->storage->delete('en', 'messages', 'php_translation_is_awesome');
    }

    public function testExportCallOnlyTransferrableStorage()
    {
        $messageCatalogue = $this->prophesize(MessageCatalogueInterface::class)->reveal();

        $this->childStorage2->export($messageCatalogue, [])->shouldBeCalledtimes(1);

        $this->storage->export($messageCatalogue, []);
    }

    public function testImportCallOnlyTransferrableStorage()
    {
        $messageCatalogue = $this->prophesize(MessageCatalogueInterface::class)->reveal();

        $this->childStorage2->import($messageCatalogue, [])->shouldBeCalledtimes(1);

        $this->storage->import($messageCatalogue, []);
    }
}
