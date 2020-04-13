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

class ArrayStorageTest extends TestCase
{
    private $messages;
    private $storage;

    public function setUp(): void
    {
        $this->messages = [
            'messages_en_foo' => new Message('foo', 'messages', 'en', 'I am the "foo" translation for English in the "messages" domain.'),
            'messages_fr_foo' => new Message('foo', 'messages', 'fr', 'Je suis la traduction de la clé "foo" en français dans le domain "messages".'),
            'messages_en_bar' => new Message('bar', 'messages', 'en', 'I am the "bar" translation for English in the "messages" domain.'),
            'messages_fr_bar' => new Message('bar', 'messages', 'fr', 'Je suis la traduction de la clé "bar" en français dans le domain "messages".'),
            'validators_en_foo' => new Message('foo', 'validators', 'en', 'I am the "foo" translation for English in the "validators" domain.'),
            'validators_fr_foo' => new Message('foo', 'validators', 'fr', 'Je suis la traduction de la clé "foo" en français dans le domain "validators".'),
            'validators_en_bar' => new Message('bar', 'validators', 'en', 'I am the "bar" translation for English in the "validators" domain.'),
            'validators_fr_bar' => new Message('bar', 'validators', 'fr', 'Je suis la traduction de la clé "bar" en français dans le domain "validators".'),
        ];

        $this->storage = new ArrayStorage();
        foreach ($this->messages as $message) {
            $this->storage->create($message);
        }
    }

    public function testDelete()
    {
        $this->assertEquals($this->messages['messages_en_foo'], $this->storage->get('en', 'messages', 'foo'));
        $this->storage->delete('en', 'messages', 'foo');
        $this->assertEquals(new Message('foo', 'messages', 'en', 'foo'), $this->storage->get('en', 'messages', 'foo'));
    }

    public function testUpdate()
    {
        $this->assertEquals($this->messages['messages_en_foo'], $this->storage->get('en', 'messages', 'foo'));
        $updatedMessage = new Message('foo', 'messages', 'en', 'Updated translation');
        $this->assertEquals($this->messages['messages_en_foo'], $this->storage->get('en', 'messages', 'foo'));
    }

    public function testExport()
    {
        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('foo', 'I will be overrided.', 'messages');

        $this->storage->export($messageCatalogue);

        $this->assertEquals($this->messages['messages_en_foo'], $this->storage->get('en', 'messages', 'foo'));
    }

    public function testImport()
    {
        $messageCatalogue = new MessageCatalogue('en');
        $messageCatalogue->set('foo', 'I will override the existing key.', 'messages');

        $this->storage->import($messageCatalogue);

        $this->assertEquals(new Message('foo', 'messages', 'en', 'I will override the existing key.'), $this->storage->get('en', 'messages', 'foo'));
    }
}
