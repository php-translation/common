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

use PHPUnit\Framework\TestCase;
use Translation\Common\Model\Message;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageTest extends TestCase
{
    public function testAccessors()
    {
        $message = new Message('key', 'domain', 'locale', 'translation');

        $this->assertEquals('key', $message->getKey());
        $this->assertEquals('domain', $message->getDomain());
        $this->assertEquals('locale', $message->getLocale());
        $this->assertEquals('translation', $message->getTranslation());

        $message = $message->withDomain('domain_foo');
        $this->assertEquals('domain_foo', $message->getDomain());
        $message = $message->withLocale('locale_foo');
        $this->assertEquals('locale_foo', $message->getLocale());
        $message = $message->withTranslation('trans_foo');
        $this->assertEquals('trans_foo', $message->getTranslation());
    }

    public function testMeta()
    {
        $message = new Message('', '', '', '', ['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $message->getAllMeta());
        $this->assertEquals('bar', $message->getMeta('foo'));

        $message = $message->withAddedMeta('key', 'value');
        $this->assertEquals('value', $message->getMeta('key'));

        $message = $message->withMeta(['new' => 'values']);
        $this->assertNull($message->getMeta('key'));
        $this->assertEquals('values', $message->getMeta('new'));
    }

    public function testImmutability()
    {
        $message = new Message('key', 'domain', 'locale', 'translation', ['foo' => 'bar']);

        $message->withDomain('domain_foo');
        $message->withLocale('locale_foo');
        $message->withTranslation('trans_foo');
        $message->withMeta(['new' => 'values']);
        $message->withAddedMeta('key', 'value');

        $this->assertEquals('key', $message->getKey());
        $this->assertEquals('domain', $message->getDomain());
        $this->assertEquals('locale', $message->getLocale());
        $this->assertEquals('translation', $message->getTranslation());
        $this->assertEquals(['foo' => 'bar'], $message->getAllMeta());
    }
}
