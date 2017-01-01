<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Common;

use Symfony\Component\Translation\MessageCatalogueInterface;

/**
 * A storage that supports import and export actions.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface TransferableStorage
{
    /**
     * Get messages from the storage into the $catalogue.
     *
     * @param MessageCatalogueInterface $catalogue
     */
    public function export(MessageCatalogueInterface $catalogue);

    /**
     * Populate the storage with all the messages in $catalogue. This action
     * should be considered as a "force merge". Existing messages in the storage
     * will be overwritten but no message will be removed.
     *
     * @param MessageCatalogueInterface $catalogue
     */
    public function import(MessageCatalogueInterface $catalogue);
}
