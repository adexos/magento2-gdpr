<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model\ResourceModel\EraseCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Adexos\Gdpr\Api\Data\EraseCustomerInterface;
use Adexos\Gdpr\Model\EraseCustomer;
use Adexos\Gdpr\Model\ResourceModel\EraseCustomer as EraseCustomerResourceModel;

/**
 * Erase Customer Scheduler Collection
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected function _construct(): void
    {
        $this->_init(EraseCustomer::class, EraseCustomerResourceModel::class);
        $this->_setIdFieldName(EraseCustomerInterface::ID);
    }
}
