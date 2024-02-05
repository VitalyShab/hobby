<?php

namespace Vitaly\Hobby\Controller\Manage;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\ForwardFactory as ResultForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory as ResultPageFactory;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly Session $customerSession,
        private readonly ResultForwardFactory $resultForwardFactory,
        private readonly ResultPageFactory $resultPageFactory
    ) {
    }

    public function execute(): ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');

            return $resultForward;
        }

        return $this->resultPageFactory->create();
    }
}
