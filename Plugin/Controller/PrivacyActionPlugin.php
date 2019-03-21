<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Plugin\Controller;

use Adexos\Gdpr\Controller\AbstractPrivacy;
use Adexos\Gdpr\Model\Config;

/**
 * Class PrivacyActionPlugin
 */
final class PrivacyActionPlugin
{
    /**
     * @var \Adexos\Gdpr\Model\Config
     */
    private $config;

    /**
     * @param \Adexos\Gdpr\Model\Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Check if the module is enabled for the current scope
     *
     * @param \Adexos\Gdpr\Controller\AbstractPrivacy $subject
     * @param callable $proceed
     * @param array ...$args
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     */
    public function aroundExecute(AbstractPrivacy $subject, callable $proceed, ...$args)
    {
        return $this->config->isModuleEnabled() ? $proceed(...$args) : $subject->forwardNoRoute();
    }
}
