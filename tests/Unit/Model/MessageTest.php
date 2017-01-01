<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\common\tests\Unit\Model;

use Translation\Common\Model\Message;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessors()
    {
        $message = new Message('key', 'domain', 'locale', 'translation');

        $this->assertEquals('key', $message->getKey());
        $this->assertEquals('domain', $message->getDomain());
        $this->assertEquals('locale', $message->getLocale());
        $this->assertEquals('translation', $message->getTranslation());

        $message->setKey('key_foo');
        $this->assertEquals('key_foo', $message->getKey());
        $message->setDomain('domain_foo');
        $this->assertEquals('domain_foo', $message->getDomain());
        $message->setLocale('locale_foo');
        $this->assertEquals('locale_foo', $message->getLocale());
        $message->setTranslation('trans_foo');
        $this->assertEquals('trans_foo', $message->getTranslation());
    }

    public function testMeta()
    {
        $message = new Message('', '', '', '', ['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $message->getAllMeta());
        $this->assertEquals('bar', $message->getMeta('foo'));

        $message->addMeta('key', 'value');
        $this->assertEquals('value', $message->getMeta('key'));

        $message->setMeta(['new' => 'values']);
        $this->assertNull($message->getMeta('key'));
        $this->assertEquals('values', $message->getMeta('new'));
    }
}
