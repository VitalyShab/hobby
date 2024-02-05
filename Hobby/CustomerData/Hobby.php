<?php

namespace Vitaly\Hobby\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Vitaly\Hobby\Model\Source\Hobby as HobbySource;

class Hobby implements SectionSourceInterface
{
    public function __construct(
        private readonly CurrentCustomer $currentCustomer,
        private readonly HobbySource     $hobbySource
    ) {
    }

    public function getSectionData(): array
    {
        if (!$this->currentCustomer->getCustomerId()) {
            return [];
        }

        $customer = $this->currentCustomer->getCustomer();
        return [
            'hobby' => $this->hobbySource->getOptionText($customer->getCustomAttribute(HobbySource::HOBBY_ATTR_CODE)
                ->getValue()),
        ];
    }
}
