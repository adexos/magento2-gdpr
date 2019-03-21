<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Controller\Privacy;

use Magento\Framework\Controller\ResultFactory;
use Adexos\Gdpr\Controller\AbstractPrivacy;

/**
 * Action Index Settings
 */
class Settings extends AbstractPrivacy
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
