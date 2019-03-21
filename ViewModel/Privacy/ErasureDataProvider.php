<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\ViewModel\Privacy;

use Magento\Cms\Block\Block;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\BlockFactory;
use Adexos\Gdpr\Api\EraseCustomerManagementInterface;
use Adexos\Gdpr\Api\EraseCustomerRepositoryInterface;
use Adexos\Gdpr\Model\Config;
use Adexos\Gdpr\Service\ErasureStrategy;

/**
 * Class ErasureDataProvider
 */
final class ErasureDataProvider extends DataObject implements ArgumentInterface
{
    /**
     * @var \Adexos\Gdpr\Api\EraseCustomerRepositoryInterface
     */
    private $eraseCustomerRepository;

    /**
     * @var \Adexos\Gdpr\Api\EraseCustomerManagementInterface
     */
    private $eraseCustomerManagement;

    /**
     * @var \Adexos\Gdpr\Model\Config
     */
    private $config;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    private $blockFactory;

    /**
     * @param \Adexos\Gdpr\Api\EraseCustomerRepositoryInterface $eraseCustomerRepository
     * @param \Adexos\Gdpr\Api\EraseCustomerManagementInterface $eraseCustomerManagement
     * @param \Adexos\Gdpr\Model\Config $config
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        EraseCustomerRepositoryInterface $eraseCustomerRepository,
        EraseCustomerManagementInterface $eraseCustomerManagement,
        Config $config,
        Session $session,
        BlockFactory $blockFactory,
        array $data = []
    ) {
        $this->eraseCustomerRepository = $eraseCustomerRepository;
        $this->eraseCustomerManagement = $eraseCustomerManagement;
        $this->config = $config;
        $this->session = $session;
        $this->blockFactory = $blockFactory;
        parent::__construct($data);
    }

    /**
     * Check if the erasure is enabled
     *
     * @return bool
     */
    public function isErasureEnabled(): bool
    {
        return $this->config->isExportEnabled();
    }

    /**
     * Retrieve the erase information html
     *
     * @return string
     */
    public function getErasureInformation(): string
    {
        if (!$this->hasData('erasure_information')) {
            $block = $this->blockFactory->createBlock(
                Block::class,
                ['data' => ['block_id' => $this->config->getErasureInformationBlockId()]]
            );
            $this->setData('erasure_information', $block->toHtml());
        }

        return (string) $this->_getData('erasure_information');
    }

    /**
     * Check if the erasure is already planned and could be canceled
     *
     * @return bool
     */
    public function canBeCanceled(): bool
    {
        if (!$this->hasData('can_be_canceled')) {
            try {
                $entity = $this->eraseCustomerRepository->getByCustomerId((int) $this->session->getCustomerId());
                $this->setData('can_be_canceled', $this->eraseCustomerManagement->canBeCanceled($entity));
            } catch (NoSuchEntityException $e) {
                $this->setData('can_be_canceled', false);
            }
        }

        return (bool) $this->_getData('can_be_canceled');
    }

    /**
     * Check if the erasure can be planned and processed
     *
     * @return bool
     */
    public function canBeProcessed(): bool
    {
        if (!$this->hasData('can_be_processed')) {
            try {
                $entity = $this->eraseCustomerRepository->getByCustomerId((int) $this->session->getCustomerId());
                $this->setData('can_be_processed', $this->eraseCustomerManagement->canBeProcessed($entity));
            } catch (NoSuchEntityException $e) {
                $this->setData('can_be_processed', true);
            }
        }

        return (bool) $this->_getData('can_be_processed');
    }

    /**
     * Check if the anonymize strategy is enabled
     *
     * @return bool
     */
    public function isAnonymizeStrategy(): bool
    {
        return ($this->config->getDefaultStrategy() === ErasureStrategy::STRATEGY_ANONYMIZE);
    }

    /**
     * Retrieve the anonymize information html
     *
     * @return string
     */
    public function getAnonymizeInformation(): string
    {
        if (!$this->hasData('anonymize_information')) {
            $block = $this->blockFactory->createBlock(
                Block::class,
                ['data' => ['block_id' => $this->config->getAnonymizeInformationBlockId()]]
            );
            $this->setData('anonymize_information', $block->toHtml());
        }

        return (string) $this->_getData('anonymize_information');
    }
}
