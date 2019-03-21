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
 * Action Undo Delete
 */
class UndoDelete extends AbstractPrivacy
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
        $this->session = $session;
        $this->eraseCustomerManagement = $eraseCustomerManagement;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $this->eraseCustomerManagement->cancel((int) $this->session->getCustomerId());
            $this->messageManager->addSuccessMessage(new Phrase('You canceled your account deletion.'));
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong, please try again later!'));
        }

        return $resultRedirect->setPath('customer/privacy/settings');
    }
}
