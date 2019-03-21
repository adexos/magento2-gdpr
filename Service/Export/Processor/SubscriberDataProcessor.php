<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Service\Export\Processor;

use Magento\Newsletter\Model\SubscriberFactory;
use Adexos\Gdpr\Service\Export\ProcessorInterface;

/**
 * Class SubscriberDataProcessor
 */
final class SubscriberDataProcessor implements ProcessorInterface
{
    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     */
    public function __construct(
        SubscriberFactory $subscriberFactory
    ) {
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(int $customerId, array $data): array
    {
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->loadByCustomerId($customerId);
        $data['subscribers'] = $subscriber->toArray();

        return $data;
    }
}
