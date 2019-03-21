<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Service\Export\Processor;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\EntityManager\Hydrator;
use Adexos\Gdpr\Model\Config;
use Adexos\Gdpr\Service\Export\ProcessorInterface;

/**
 * Class CustomerDataProcessor
 */
final class CustomerDataProcessor implements ProcessorInterface
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Framework\EntityManager\Hydrator
     */
    private $hydrator;

    /**
     * @var \Adexos\Gdpr\Model\Config
     */
    private $config;

    /**
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\EntityManager\Hydrator $hydrator
     * @param \Adexos\Gdpr\Model\Config $config
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        Hydrator $hydrator,
        Config $config
    ) {
        $this->customerRepository = $customerRepository;
        $this->hydrator = $hydrator;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(int $customerId, array $data): array
    {
        $customerData = $this->hydrator->extract($this->customerRepository->getById($customerId));
        $data['customer'] = $this->generateArray($customerData);

        return $data;
    }

    /**
     * Collect the customer data to export
     *
     * @param array $customerData
     * @return array
     */
    private function generateArray(array $customerData): array
    {
        $data = [];

        foreach ($this->config->getExportCustomerAttributes() as $attributeCode) {
            if (isset($customerData[$attributeCode])) {
                $data[$attributeCode] = $customerData[$attributeCode];
            }
        }

        return $data;
    }
}
