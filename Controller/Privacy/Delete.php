<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Adexos\Gdpr\Controller\Privacy;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Phrase;
use Adexos\Gdpr\Api\EraseCustomerManagementInterface;
use Adexos\Gdpr\Controller\AbstractPrivacy;

/**
 * Action Index Delete
 */
class Delete extends AbstractPrivacy
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Adexos\Gdpr\Api\EraseCustomerManagementInterface
     */
    private $eraseCustomerManagement;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $session
     * @param \Adexos\Gdpr\Api\EraseCustomerManagementInterface $eraseCustomerManagement
     */
    public function __construct(
        Context $context,
        Session $session,
        EraseCustomerManagementInterface $eraseCustomerManagement
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->eraseCustomerManagement = $eraseCustomerManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->eraseCustomerManagement->exists((int) $this->session->getCustomerId())) {
            $this->messageManager->addErrorMessage(new Phrase('Your account is already being removed.'));
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setRefererOrBaseUrl();

            return $resultRedirect;
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
