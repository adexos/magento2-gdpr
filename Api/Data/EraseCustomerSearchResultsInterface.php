<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */

namespace Adexos\Gdpr\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface EraseCustomerSearchResultsInterface
 * @api
 */
interface EraseCustomerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Retrieve the erase customer schedulers list
     *
     * @return \Adexos\Gdpr\Api\Data\EraseCustomerInterface[]
     */
    public function getItems(): array;

    /**
     * Set the erase customer schedulers list
     *
     * @param \Adexos\Gdpr\Api\Data\EraseCustomerInterface[] $items
     * @return \Adexos\Gdpr\Api\Data\EraseCustomerSearchResultsInterface
     */
    public function setItems(array $items): EraseCustomerSearchResultsInterface;
}
