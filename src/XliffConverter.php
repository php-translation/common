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

use Symfony\Component\Translation\Dumper\XliffFileDumper;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Utility class to convert between a MessageCatalogue and XLIFF file content.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class XliffConverter
{
    /**
     * Create a catalogue from the contents of a XLIFF file.
     */
    public static function contentToCatalogue(string $content, string $locale, string $domain): MessageCatalogue
    {
        $file = sys_get_temp_dir().'/'.uniqid('xliff', true);
        file_put_contents($file, $content);

        return (new XliffFileLoader())->load($file, $locale, $domain);
    }

    public static function catalogueToContent(MessageCatalogue $catalogue, string $domain, array $options = []): string
    {
        if (!\array_key_exists('xliff_version', $options)) {
            // Set default value for xliff version.
            $options['xliff_version'] = '2.0';
        }

        return (new XliffFileDumper())->formatCatalogue($catalogue, $domain, $options);
    }
}
