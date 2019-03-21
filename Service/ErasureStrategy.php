<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Service;

use Adexos\Gdpr\Model\Config\ErasureComponentStrategy;

/**
 * Class PrivacyStrategy
 * @api
 */
final class ErasureStrategy
{
    /**#@+
     * Strategy Constant Values
     */
    public const STRATEGY_ANONYMIZE = 'anonymize';
    public const STRATEGY_DELETE = 'delete';
    /**#@-*/

    /**
     * @var \Adexos\Gdpr\Model\Config\ErasureComponentStrategy
     */
    private $componentStrategy;

    /**
     * @var \Adexos\Gdpr\Service\AnonymizeManagement
     */
    private $anonymizeManagement;

    /**
     * @var \Adexos\Gdpr\Service\DeleteManagement
     */
    private $deleteManagement;

    /**
     * @param \Adexos\Gdpr\Model\Config\ErasureComponentStrategy $componentStrategy
     * @param \Adexos\Gdpr\Service\AnonymizeManagement $anonymizeManagement
     * @param \Adexos\Gdpr\Service\DeleteManagement $deleteManagement
     */
    public function __construct(
        ErasureComponentStrategy $componentStrategy,
        AnonymizeManagement $anonymizeManagement,
        DeleteManagement $deleteManagement
    ) {
        $this->componentStrategy = $componentStrategy;
        $this->anonymizeManagement = $anonymizeManagement;
        $this->deleteManagement = $deleteManagement;
    }

    /**
     * Execute the processors by strategy type
     *
     * @param int $customerId
     * @return bool
     */
    public function execute(int $customerId): bool
    {
        foreach ($this->componentStrategy->getComponentsByStrategy(self::STRATEGY_ANONYMIZE) as $processorName) {
            $this->anonymizeManagement->executeProcessor($processorName, $customerId);
        }
        foreach ($this->componentStrategy->getComponentsByStrategy(self::STRATEGY_DELETE) as $processorName) {
            $this->deleteManagement->executeProcessor($processorName, $customerId);
        }

        return true;
    }

    /**
     * Execute the processor by strategy type
     *
     * @param string $processorName
     * @param int $customerId
     * @return bool
     */
    public function executeProcessorStrategy(string $processorName, int $customerId): bool
    {
        $strategyType = $this->componentStrategy->getComponentStrategy($processorName);

        switch ($strategyType) {
            case self::STRATEGY_ANONYMIZE:
                $result = $this->anonymizeManagement->executeProcessor($processorName, $customerId);
                break;
            case self::STRATEGY_DELETE:
                $result = $this->deleteManagement->executeProcessor($processorName, $customerId);
                break;
            default:
                throw new \InvalidArgumentException(\sprintf('Unknown strategy type "%s".', $strategyType));
                break;
        }

        return $result;
    }
}
