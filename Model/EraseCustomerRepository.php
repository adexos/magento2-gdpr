<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Adexos\Gdpr\Api\Data\EraseCustomerInterface;
use Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory;
use Adexos\Gdpr\Api\Data\EraseCustomerSearchResultsInterfaceFactory;
use Adexos\Gdpr\Api\EraseCustomerRepositoryInterface;
use Adexos\Gdpr\Model\ResourceModel\EraseCustomer as EraseCustomerResource;
use Adexos\Gdpr\Model\ResourceModel\EraseCustomer\CollectionFactory;

/**
 * Class EraseCustomerRepository
 */
final class EraseCustomerRepository implements EraseCustomerRepositoryInterface
{
    /**
     * @var \Adexos\Gdpr\Model\ResourceModel\EraseCustomer
     */
    private $eraseCustomerResource;

    /**
     * @var \Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory
     */
    private $eraseCustomerFactory;

    /**
     * @var \Adexos\Gdpr\Model\ResourceModel\EraseCustomer\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Adexos\Gdpr\Api\Data\EraseCustomerSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var \Adexos\Gdpr\Api\Data\EraseCustomerInterface[]
     */
    private $instances = [];

    /**
     * @var \Adexos\Gdpr\Api\Data\EraseCustomerInterface[]
     */
    private $instancesByCustomer = [];

    /**
     * @param \Adexos\Gdpr\Model\ResourceModel\EraseCustomer $eraseCustomerResource
     * @param \Adexos\Gdpr\Api\Data\EraseCustomerInterfaceFactory $eraseCustomerFactory
     * @param \Adexos\Gdpr\Model\ResourceModel\EraseCustomer\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Adexos\Gdpr\Api\Data\EraseCustomerSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        EraseCustomerResource $eraseCustomerResource,
        EraseCustomerInterfaceFactory $eraseCustomerFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        EraseCustomerSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->eraseCustomerResource = $eraseCustomerResource;
        $this->eraseCustomerFactory = $eraseCustomerFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(EraseCustomerInterface $entity): EraseCustomerInterface
    {
        try {
            $this->eraseCustomerResource->save($entity);
            $entity = $this->getById($entity->getEntityId(), true);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(new Phrase('Could not save the entity.'), $e);
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getById(int $entityId, bool $forceReload = false): EraseCustomerInterface
    {
        if ($forceReload || !isset($this->instances[$entityId])) {
            /** @var \Adexos\Gdpr\Api\Data\EraseCustomerInterface $entity */
            $entity = $this->eraseCustomerFactory->create();
            $this->eraseCustomerResource->load($entity, $entityId, EraseCustomerInterface::ID);

            if (!$entity->getEntityId()) {
                throw new NoSuchEntityException(new Phrase('Entity with id "%1" does not exists.', [$entityId]));
            }

            $this->instances[$entityId] = $entity;
            $this->instancesByCustomer[$entity->getCustomerId()] = $entity;
        }

        return $this->instances[$entityId];
    }

    /**
     * {@inheritdoc}
     */
    public function getByCustomerId(int $entityId, bool $forceReload = false): EraseCustomerInterface
    {
        if ($forceReload || !isset($this->instancesByCustomer[$entityId])) {
            /** @var \Adexos\Gdpr\Api\Data\EraseCustomerInterface $entity */
            $entity = $this->eraseCustomerFactory->create();
            $this->eraseCustomerResource->load($entity, $entityId, EraseCustomerInterface::CUSTOMER_ID);

            if (!$entity->getEntityId()) {
                throw new NoSuchEntityException(
                    new Phrase('Entity with customer id "%1" does not exist.', [$entityId])
                );
            }

            $this->instances[$entity->getEntityId()] = $entity;
            $this->instancesByCustomer[$entityId] = $entity;
        }

        return $this->instancesByCustomer[$entityId];
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var \Adexos\Gdpr\Model\ResourceModel\EraseCustomer\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Adexos\Gdpr\Api\Data\EraseCustomerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(EraseCustomerInterface $entity): bool
    {
        try {
            if (isset($this->instances[$entity->getEntityId()])) {
                unset($this->instances[$entity->getEntityId()]);
            }
            if (isset($this->instancesByCustomer[$entity->getCustomerId()])) {
                unset($this->instancesByCustomer[$entity->getCustomerId()]);
            }
            $this->eraseCustomerResource->delete($entity);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                new Phrase('Could not delete entity with id "%1".', [$entity->getEntityId()]), $e
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->getById($entityId));
    }
}
