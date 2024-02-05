<?php

namespace Vitaly\Hobby\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Hobby extends AbstractSource
{
    public const HOBBY_ATTR_CODE = 'hobby';

    public const YOGA = 1;
    public const TRAVELLING = 2;
    public const HIKING = 3;

    public function getAllOptions(): ?array
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => '', 'label' => __('Not Selected')],
                ['value' => self::YOGA, 'label' => __('Yoga')],
                ['value' => self::TRAVELLING, 'label' => __('Travelling')],
                ['value' => self::HIKING, 'label' => __('Hiking')],
            ];
        }

        return $this->_options;
    }
}
