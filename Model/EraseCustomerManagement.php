<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\Stdlib\DateTime as DateTimeFormat;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Adexos\Gdpr\Api\Data\EraseCustomerInterface;
use Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory;
use Adexos\Gdpr\Api\EraseCustomerManagementInterface;
use Adexos\Gdpr\Api\EraseCustomerRepositoryInterface;
use Adexos\Gdpr\Service\ErasureStrategy;

/**
 * Class EraseCustomerManagement
 */
final class EraseCustomerManagement implements EraseCustomerManagementInterface
{
    /**
     * @var \Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory
     */
    private $eraseCustomerFactory;

    /**
     * @var \Adexos\Gdpr\Api\EraseCustomerRepositoryInterface
     */
    private $eraseCustomerRepository;

    /**
     * @var \Adexos\Gdpr\Service\ErasureStrategy
     */
    private $erasureStrategy;

    /**
     * @var \Adexos\Gdpr\Model\Config
     */
    private $config;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $localeDate;

    /**
     * @param \Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory $eraseCustomerFactory
     * @param \Adexos\Gdpr\Api\EraseCustomerRepositoryInterface $eraseCustomerRepository
     * @param \Adexos\Gdpr\Service\ErasureStrategy $erasureStrategy
     * @param \Adexos\Gdpr\Model\Config $config
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $localeDate
     */
    public function __construct(
        EraseCustomerInterfaceFactory $eraseCustomerFactory,
        EraseCustomerRepositoryInterface $eraseCustomerRepository,
        ErasureStrategy $erasureStrategy,
        Config $config,
        DateTime $localeDate
    ) {
        $this->eraseCustomerFactory = $eraseCustomerFactory;
        $this->eraseCustomerRepository = $eraseCustomerRepository;
        $this->erasureStrategy = $erasureStrategy;
        $this->config = $config;
        $this->localeDate = $localeDate;
    }

    /**
     * {@inheritdoc}
     */
    public function create(int $customerId): EraseCustomerInterface
    {
        if ($this->exists($customerId)) {
            throw new AlreadyExistsException(new Phrase('Entity for customer id "%1" already exists.', [$customerId]));
        }

        /** @var \Adexos\Gdpr\Api\Data\EraseCustomerInterface $entity */
        $entity = $this->eraseCustomerFactory->create();
        $entity->setCustomerId($customerId);
        $entity->setState(EraseCustomerInterface::STATE_PENDING);
        $entity->setStatus(EraseCustomerInterface::STATUS_READY);
        $entity->setScheduledAt($this->retrieveScheduledAt());

        return $this->eraseCustomerRepository->save($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(int $customerId): bool
    {
        $entity = $this->eraseCustomerRepository->getByCustomerId($customerId);

        if (!$this->canBeCanceled($entity)) {
            throw new LocalizedException(
                new Phrase('Customer with id "%1" is already being removed.', [$customerId])
            );
        }

        return $this->eraseCustomerRepository->delete($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function process(EraseCustomerInterface $entity): EraseCustomerInterface
    {
        if (!$this->canBeProcessed($entity)) {
            throw new LocalizedException(
                new Phrase('Entity with id "%1" could not be processed.', [$entity->getEntityId()])
            );
        }

        $entity->setState(EraseCustomerInterface::STATE_PROCESSING);
        $entity->setStatus(EraseCustomerInterface::STATUS_RUNNING);
        $entity = $this->eraseCustomerRepository->save($entity);

        try {
            $this->erasureStrategy->execute($entity->getCustomerId());
            $entity->setState(EraseCustomerInterface::STATE_COMPLETE);
            $entity->setStatus(EraseCustomerInterface::STATUS_SUCCEED);
            $entity->setErasedAt($this->localeDate->gmtDate());
        } catch (\Exception $e) {
            $entity->setState(EraseCustomerInterface::STATE_PROCESSING);
            $entity->setStatus(EraseCustomerInterface::STATUS_FAILED);
        }

        return $this->eraseCustomerRepository->save($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(int $customerId): bool
    {
        try {
            $this->eraseCustomerRepository->getByCustomerId($customerId);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canBeCanceled(EraseCustomerInterface $entity): bool
    {
        return $entity->getState() === EraseCustomerInterface::STATE_PENDING
            && $entity->getStatus() === EraseCustomerInterface::STATUS_READY;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeProcessed(EraseCustomerInterface $entity): bool
    {
        return ($entity->getState() === EraseCustomerInterface::STATE_PENDING
            && $entity->getStatus() === EraseCustomerInterface::STATUS_READY)
            || ($entity->getState() === EraseCustomerInterface::STATE_PROCESSING
            && $entity->getStatus() === EraseCustomerInterface::STATUS_FAILED);
    }

    /**
     * Retrieve the final scheduled at date from config
     *
     * @return string
     */
    private function retrieveScheduledAt(): string
    {
        return $this->localeDate->gmtDate(
            DateTimeFormat::DATETIME_PHP_FORMAT,
            $this->config->getErasureTimeLapse() + $this->localeDate->gmtTimestamp()
        );
    }
}
