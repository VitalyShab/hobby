<?php

namespace Vitaly\Hobby\ViewModel\Customer;

use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vitaly\Hobby\Model\Source\Hobby as HobbySource;

class Hobby implements ArgumentInterface
{
    public function __construct(
        private readonly HobbySource $hobbySource,
        private readonly Session $customerSession,
        private readonly UrlInterface $url
    ) {
    }

    public function getAllOptions(): array
    {
        return $this->hobbySource->getAllOptions() ?: [];
    }

    public function isSelectedOption(int $value): bool
    {
        return $this->customerSession->getCustomer()->getHobby() == $value;
    }

    public function getAction(): string
    {
        return $this->url->getUrl('hobby/manage/save');
    }
}
