<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Web200\Seo\Model\Page\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class PageLayout
 */
class PageMetaRobots implements OptionSourceInterface
{

    /**
     * Values
     *
     * @var string[] VALUES
     */
    public const VALUES = [
        'INDEX, FOLLOW',
        'NOINDEX, FOLLOW',
        'INDEX, NOFOLLOW',
        'NOINDEX, NOFOLLOW'
    ];

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $array   = [];
        $array[] = ['value' => '', 'label' => ' '];
        foreach (self::VALUES as $value) {
            $array[] = [
                'value' => $value,
                'label' => $value
            ];
        }

        return $array;
    }
}
