<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Adexos\Gdpr\Api\Data\EraseCustomerInterface;

/**
 * Erase Customer Scheduler Resource Model
 */
final class EraseCustomer extends AbstractDb
{
    public const TABLE = 'odexos_gdpr_erase_customer';

    /**
     * {@inheritdoc}
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE, EraseCustomerInterface::ID);
    }
}
