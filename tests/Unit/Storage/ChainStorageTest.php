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
use Symfony\Component\Translation\MessageCatalogue;
use Translation\Common\Model\Message;
use Translation\Common\Storage\ArrayStorage;
use Translation\Common\Storage\ChainStorage;
use Translation\Common\Storage\StorageInterface;

class ChainStorageTest extends TestCase
{
    private $childStorage1;
    private $childStorage2;
    private $storage;

    public function setUp(): void
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

        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1)->willReturn($expectedMessage);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldNotBeCalled();

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertSame($expectedMessage, $message);
    }

    public function testGetWithMessageInSecondStorage()
    {
        $expectedMessage = new Message('PHP Translation IS awesome!');

        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1)->willReturn(null);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1)->willReturn($expectedMessage);

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertSame($expectedMessage, $message);
    }

    public function testGetWithMessageNotFound()
    {
        $this->childStorage1->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1)->willReturn(null);
        $this->childStorage2->get('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1)->willReturn(null);

        $message = $this->storage->get('en', 'messages', 'php_translation_is_awesome');
        $this->assertNull($message);
    }

    public function testCreateCallAllStorages()
    {
        $message = new Message('PHP Translation IS awesome!');

        $this->childStorage1->create($message)->shouldBeCalledTimes(1);
        $this->childStorage2->create($message)->shouldBeCalledTimes(1);

        $this->storage->create($message);
    }

    public function testUpdateCallAllStorages()
    {
        $message = new Message('PHP Translation IS awesome!');

        $this->childStorage1->update($message)->shouldBeCalledTimes(1);
        $this->childStorage2->update($message)->shouldBeCalledTimes(1);

        $this->storage->update($message);
    }

    public function testDeleteCallAllStorages()
    {
        $this->childStorage1->delete('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1);
        $this->childStorage2->delete('en', 'messages', 'php_translation_is_awesome')->shouldBeCalledTimes(1);

        $this->storage->delete('en', 'messages', 'php_translation_is_awesome');
    }

    public function testExport()
    {
        $firstStorage = new ArrayStorage();
        $firstStorage->create(new Message('common', 'messages', 'en', 'Translation from first storage'));
        $firstStorage->create(new Message('foo', 'messages', 'en', 'Only in first storage'));
        $firstStorage->create(new Message('baz', 'domain_1', 'en', 'Baz in domain 1'));
        $secondStorage = new ArrayStorage();
        $secondStorage->create(new Message('common', 'messages', 'en', 'Translation from second storage'));
        $secondStorage->create(new Message('bar', 'messages', 'en', 'Only in second storage'));
        $secondStorage->create(new Message('baz', 'domain_2', 'en', 'Baz in domain 2'));

        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('common', 'Translation from the existing catalogue.', 'messages');
        $messageCatalogue->set('baz', 'Baz in domain 3', 'domain_3');

        $storage = new ChainStorage([$firstStorage, $secondStorage]);
        $storage->export($messageCatalogue, []);

        $expectedMessages = [
            'messages' => [
                'common' => 'Translation from second storage',
                'foo' => 'Only in first storage',
                'bar' => 'Only in second storage',
            ],
            'domain_3' => [
                'baz' => 'Baz in domain 3',
            ],
            'domain_1' => [
                'baz' => 'Baz in domain 1',
            ],
            'domain_2' => [
                'baz' => 'Baz in domain 2',
            ],
        ];

        $this->assertSame($expectedMessages, $messageCatalogue->all());
    }

    public function testExportDown()
    {
        $firstStorage = new ArrayStorage();
        $firstStorage->create(new Message('common', 'messages', 'en', 'Translation from first storage'));
        $firstStorage->create(new Message('foo', 'messages', 'en', 'Only in first storage'));
        $firstStorage->create(new Message('baz', 'domain_1', 'en', 'Baz in domain 1'));
        $secondStorage = new ArrayStorage();
        $secondStorage->create(new Message('common', 'messages', 'en', 'Translation from second storage'));
        $secondStorage->create(new Message('bar', 'messages', 'en', 'Only in second storage'));
        $secondStorage->create(new Message('baz', 'domain_2', 'en', 'Baz in domain 2'));

        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('common', 'Translation from the existing catalogue.', 'messages');
        $messageCatalogue->set('baz', 'Baz in domain 3', 'domain_3');

        $storage = new ChainStorage([$firstStorage, $secondStorage]);
        $storage->export($messageCatalogue, ['direction' => ChainStorage::DIRECTION_DOWN]);

        $expectedMessages = [
            'messages' => [
                'common' => 'Translation from first storage',
                'bar' => 'Only in second storage',
                'foo' => 'Only in first storage',
            ],
            'domain_3' => [
                'baz' => 'Baz in domain 3',
            ],
            'domain_2' => [
                'baz' => 'Baz in domain 2',
            ],
            'domain_1' => [
                'baz' => 'Baz in domain 1',
            ],
        ];

        $this->assertSame($expectedMessages, $messageCatalogue->all());
    }

    public function testImport()
    {
        $firstStorage = new ArrayStorage();
        $firstStorage->create(new Message('common', 'messages', 'en', 'Translation from first storage'));
        $firstStorage->create(new Message('foo', 'messages', 'en', 'Only in first storage'));
        $firstStorage->create(new Message('baz', 'domain_1', 'en', 'Baz in domain 1'));
        $secondStorage = new ArrayStorage();
        $secondStorage->create(new Message('common', 'messages', 'en', 'Translation from second storage'));
        $secondStorage->create(new Message('bar', 'messages', 'en', 'Only in second storage'));
        $secondStorage->create(new Message('baz', 'domain_2', 'en', 'Baz in domain 2'));

        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('common', 'Translation from the existing catalogue.', 'messages');
        $messageCatalogue->set('baz', 'Baz in domain 3', 'domain_3');

        $storage = new ChainStorage([$firstStorage, $secondStorage]);
        $storage->import($messageCatalogue, []);

        $expectedMessagesInFirstStorage = [
            'messages' => [
                'common' => 'Translation from the existing catalogue.',
                'foo' => 'Only in first storage',
            ],
            'domain_1' => [
                'baz' => 'Baz in domain 1',
            ],
            'domain_3' => [
                'baz' => 'Baz in domain 3',
            ],
        ];

        $firstStorage->export($messagesInFirstStorage = new MessageCatalogue('en'), []);
        $this->assertSame($expectedMessagesInFirstStorage, $messagesInFirstStorage->all());

        $expectedMessagesInSecondStorage = [
            'messages' => [
                'common' => 'Translation from the existing catalogue.',
                'bar' => 'Only in second storage',
            ],
            'domain_2' => [
                'baz' => 'Baz in domain 2',
            ],
            'domain_3' => [
                'baz' => 'Baz in domain 3',
            ],
        ];

        $secondStorage->export($messagesInSecondStorage = new MessageCatalogue('en'), []);
        $this->assertSame($expectedMessagesInSecondStorage, $messagesInSecondStorage->all());
    }
}
