<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\SymfonyStorage\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\Reader\TranslationReader;
use Symfony\Component\Translation\Writer\TranslationWriter;
use Translation\Common\Model\Message;
use Translation\SymfonyStorage\FileStorage;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FileStorageTest extends TestCase
{
    public function testConstructor()
    {
        $storage = new FileStorage(new TranslationWriter(), $this->createTranslationLoader(), ['foo']);
        $this->assertInstanceOf(FileStorage::class, $storage);
    }

    public function testConstructorEmptyArray()
    {
        $this->expectException(\LogicException::class);

        new FileStorage(new TranslationWriter(), $this->createTranslationLoader(), []);
    }

    public function testCreateNewCatalogue()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->once())
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->isInstanceOf(MessageCatalogueInterface::class),
                'xlf',
                ['path' => 'foo', 'xliff_version' => '2.0']
            );

        $storage = new FileStorage($writer, $this->createTranslationLoader(), ['foo']);
        $storage->create(new Message('key', 'domain', 'en', 'Message'));

        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->once())
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->isInstanceOf(MessageCatalogueInterface::class),
                'format',
                ['path' => 'bar', 'default_output_format' => 'format', 'xliff_version' => '2.0']
            );

        $storage = new FileStorage($writer, $this->createTranslationLoader(), ['bar'], ['default_output_format' => 'format']);
        $storage->create(new Message('key', 'domain', 'en', 'Message'));
    }

    public function testCreateExistingCatalogue()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->once())
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->isInstanceOf(MessageCatalogueInterface::class),
                'xlf',
                ['path' => $this->getFixturePath(), 'xliff_version' => '2.0']
            );

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, ['foo', $this->getFixturePath()]);

        $storage->create(new Message('key', 'messages', 'en', 'Translation'));
    }

    public function testGet()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, [$this->getFixturePath()]);

        $this->assertEquals('Bazbar', $storage->get('en', 'messages', 'test_1')->getTranslation());

        // Missing locale
        $this->assertEquals('test_1', $storage->get('sv', 'messages', 'test_1')->getTranslation());

        // Missing domain
        $this->assertEquals('test_1', $storage->get('en', 'xx', 'test_1')->getTranslation());

        // Missing key
        $this->assertEquals('miss', $storage->get('en', 'messages', 'miss')->getTranslation());
    }

    public function testUpdate()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();
        $writer->expects($this->exactly(2))
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->isInstanceOf(MessageCatalogueInterface::class),
                'xlf',
                ['path' => $this->getFixturePath(), 'xliff_version' => '2.0']
            );

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, [$this->getFixturePath()]);

        $storage->update(new Message('key', 'messages', 'en', 'Translation'));
        $storage->update(new Message('test_1', 'messages', 'en', 'Translation'));
    }

    public function testDelete()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();

        $writer->expects($this->once())
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->callback(function (MessageCatalogueInterface $catalogue) {
                    return !$catalogue->defines('test_0', 'messages');
                }),
                'xlf',
                ['path' => $this->getFixturePath(), 'xliff_version' => '2.0']
            );

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, [$this->getFixturePath()]);

        $storage->delete('en', 'messages', 'test_0');
    }

    public function testImport()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->setMethods([$this->getMethodNameToWriteTranslations()])
            ->disableOriginalConstructor()
            ->getMock();

        $writer->expects($this->once())
            ->method($this->getMethodNameToWriteTranslations())
            ->with(
                $this->callback(function (MessageCatalogueInterface $catalogue) {
                    return $catalogue->defines('test_4711', 'messages');
                }),
                'xlf',
                ['path' => $this->getFixturePath(), 'xliff_version' => '2.0']
            );

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, [$this->getFixturePath()]);
        $catalogue = new MessageCatalogue('en', ['messages' => ['test_4711' => 'foobar']]);

        $storage->import($catalogue);
    }

    public function testExport()
    {
        $writer = $this->getMockBuilder(TranslationWriter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $loader = $this->createTranslationLoader();
        $loader->addLoader('xlf', new XliffFileLoader());
        $storage = new FileStorage($writer, $loader, [$this->getFixturePath()]);

        $catalogue = new MessageCatalogue('en');
        $storage->export($catalogue);
        $this->assertTrue($catalogue->defines('test_0', 'messages'));
        $this->assertTrue($catalogue->defines('test_1', 'messages'));

        // Wrong locale
        $catalogue = new MessageCatalogue('sv');
        $storage->export($catalogue);
        $this->assertFalse($catalogue->defines('test_0', 'messages'));
    }

    /**
     * @return string
     */
    private function getFixturePath()
    {
        return realpath(__DIR__.'/../Fixtures/single-file');
    }

    /**
     * @return TranslationReader
     */
    private function createTranslationLoader()
    {
        return new TranslationReader();
    }

    private function getMethodNameToWriteTranslations()
    {
        if (method_exists(TranslationWriter::class, 'write')) {
            return 'write';
        }

        return 'writeTranslations';
    }
}
