<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\SymfonyStorage;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\Reader\TranslationReaderInterface;
use Symfony\Component\Translation\Writer\TranslationWriterInterface;
use Translation\Common\Model\Message;
use Translation\Common\Model\MessageInterface;
use Translation\Common\Storage;
use Translation\Common\TransferableStorage;

/**
 * This storage uses Symfony's writer and loader.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class FileStorage implements Storage, TransferableStorage
{
    /**
     * @var TranslationWriterInterface
     */
    private $writer;

    /**
     * @var TranslationReaderInterface
     */
    private $reader;

    /**
     * @var array directory path
     */
    private $dir;

    /**
     * @var array with option to the dumper
     */
    private $options;

    /**
     * @var MessageCatalogue[] Fetched catalogues
     */
    private $catalogues;

    public function __construct(TranslationWriterInterface $writer, TranslationReaderInterface $reader, array $dir, array $options = [])
    {
        if (empty($dir)) {
            throw new \LogicException('Third parameter of FileStorage cannot be empty');
        }

        if (!\array_key_exists('xliff_version', $options)) {
            // Set default value for xliff version.
            $options['xliff_version'] = '2.0';
        }

        $this->writer = $writer;
        $this->reader = $reader;
        $this->dir = $dir;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $locale, string $domain, string $key): ?MessageInterface
    {
        $catalogue = $this->getCatalogue($locale);
        $translation = $catalogue->get($key, $domain);

        return new Message($key, $domain, $locale, $translation);
    }

    /**
     * {@inheritdoc}
     */
    public function create(MessageInterface $m): void
    {
        $catalogue = $this->getCatalogue($m->getLocale());
        if (!$catalogue->defines($m->getKey(), $m->getDomain())) {
            $catalogue->set($m->getKey(), $m->getTranslation(), $m->getDomain());
            $this->writeCatalogue($catalogue, $m->getLocale(), $m->getDomain());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(MessageInterface $m): void
    {
        $catalogue = $this->getCatalogue($m->getLocale());
        $catalogue->set($m->getKey(), $m->getTranslation(), $m->getDomain());
        $this->writeCatalogue($catalogue, $m->getLocale(), $m->getDomain());
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $locale, string $domain, string $key): void
    {
        $catalogue = $this->getCatalogue($locale);
        $messages = $catalogue->all($domain);
        unset($messages[$key]);

        $catalogue->replace($messages, $domain);
        $this->writeCatalogue($catalogue, $locale, $domain);
    }

    /**
     * {@inheritdoc}
     */
    public function export(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        $locale = $catalogue->getLocale();
        $catalogue->addCatalogue($this->getCatalogue($locale));
    }

    /**
     * {@inheritdoc}
     */
    public function import(MessageCatalogueInterface $catalogue, array $options = []): void
    {
        $domains = $catalogue->getDomains();
        foreach ($domains as $domain) {
            $this->writeCatalogue($catalogue, $catalogue->getLocale(), $domain);
        }
    }

    private function writeCatalogue(MessageCatalogueInterface $catalogue, string $locale, string $domain): void
    {
        $resources = $catalogue->getResources();
        $options = $this->options;
        $written = false;
        // $intlDomainSuffix = '(\\' . MessageCatalogueInterface::INTL_DOMAIN_SUFFIX . ')'; # not available in older Symfony versions
        $intlDomainSuffix = '(\\'.'+intl-icu'.')';
        $searchPatternWithIntl = '|/'.$domain.$intlDomainSuffix.'\.'.$locale.'\.([a-z]+)$|';
        $searchPatternWithoutIntl = str_replace($intlDomainSuffix, '', $searchPatternWithIntl);
        foreach ($resources as $resource) {
            $path = (string) $resource;
            if (preg_match($searchPatternWithIntl, $path, $matches)) {
                $options['path'] = str_replace($matches[0], '', $path);
                $this->writer->write($catalogue, $matches[2], $options);
                $written = true;
            } elseif (preg_match($searchPatternWithoutIntl, $path, $matches)) {
                $options['path'] = str_replace($matches[0], '', $path);
                $this->writer->write($catalogue, $matches[1], $options);
                $written = true;
            }
        }

        if ($written) {
            // We have written the translation to a file.
            return;
        }

        $options['path'] = reset($this->dir);
        $format = isset($options['default_output_format']) ? $options['default_output_format'] : 'xlf';
        $this->writer->write($catalogue, $format, $options);
    }

    private function getCatalogue(string $locale): MessageCatalogue
    {
        if (empty($this->catalogues[$locale])) {
            $this->loadCatalogue($locale, $this->dir);
        }

        return $this->catalogues[$locale];
    }

    private function loadCatalogue(string $locale, array $dirs): void
    {
        $currentCatalogue = new MessageCatalogue($locale);
        foreach ($dirs as $path) {
            if (is_dir($path)) {
                $this->reader->read($path, $currentCatalogue);
            }
        }

        $this->catalogues[$locale] = $currentCatalogue;
    }
}
