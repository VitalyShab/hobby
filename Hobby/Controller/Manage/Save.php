<?php

declare(strict_types=1);

namespace Vitaly\Hobby\Controller\Manage;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\ForwardFactory as ResultForwardFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\Manager;
use Vitaly\Hobby\Model\Source\Hobby;

class Save implements HttpPostActionInterface, HttpGetActionInterface
{
    public function __construct(
        private readonly Session $customerSession,
        private readonly Validator $formKeyValidator,
        private readonly CustomerRepository $customerRepository,
        private readonly RequestInterface $request,
        private readonly RedirectFactory $redirectFactory,
        private readonly ResultForwardFactory $resultForwardFactory,
        private readonly Manager $messageManager
    ) {
    }

    public function execute(): ResultInterface
    {
        if (!$this->formKeyValidator->validate($this->request)) {
            return $this->redirectFactory->create()->setPath('customer/account/');
        }

        if (!$this->customerSession->isLoggedIn()) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');

            return $resultForward;
        }

        try {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            $customer->setCustomAttribute(Hobby::HOBBY_ATTR_CODE, $this->request->getParam('hobby', ''));
            $this->customerRepository->save($customer);
            $this->messageManager->addSuccessMessage(__('We have saved your hobby.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving your hobby.'));
        }

        return $this->redirectFactory->create()->setPath('customer/account/');
    }
}
