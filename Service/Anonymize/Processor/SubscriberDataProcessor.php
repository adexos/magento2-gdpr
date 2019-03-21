<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Service\Anonymize\Processor;

use Magento\Newsletter\Model\ResourceModel\Subscriber as ResourceSubscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Adexos\Gdpr\Service\Anonymize\AnonymizeTool;
use Adexos\Gdpr\Service\Anonymize\ProcessorInterface;

/**
 * Class SubscriberDataProcessor
 */
final class SubscriberDataProcessor implements ProcessorInterface
{
    /**
     * @var \Adexos\Gdpr\Service\Anonymize\AnonymizeTool
     */
    private $anonymizeTool;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber
     */
    private $subscriberResourceModel;

    /**
     * @param \Adexos\Gdpr\Service\Anonymize\AnonymizeTool $anonymizeTool
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber $subscriberResourceModel
     */
    public function __construct(
        AnonymizeTool $anonymizeTool,
        SubscriberFactory $subscriberFactory,
        ResourceSubscriber $subscriberResourceModel
    ) {
        $this->anonymizeTool = $anonymizeTool;
        $this->subscriberFactory = $subscriberFactory;
        $this->subscriberResourceModel = $subscriberResourceModel;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function execute(int $customerId): bool
    {
        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->loadByCustomerId($customerId);
        $subscriber->setEmail($this->anonymizeTool->anonymousEmail());
        $this->subscriberResourceModel->save($subscriber);

        return true;
    }
}
