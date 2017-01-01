<?php

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
     * Populate the storage with all the messages in $cataloge.
     *
     * @param MessageCatalogueInterface $catalogue
     */
    public function import(MessageCatalogueInterface $catalogue);
}
