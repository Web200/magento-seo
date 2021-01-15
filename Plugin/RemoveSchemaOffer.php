<?php

namespace Web200\Seo\Plugin;

use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Render\PriceBox;

/**
 * Class RemoveSchemaOffer
 *
 * @package   Web200\Seo\Plugin
 * @author    Web200 <contact@web200.fr>
 * @copyright 2019 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class RemoveSchemaOffer
{
    /**
     * Before Render Amount
     *
     * @param PriceBox        $subject
     * @param AmountInterface $amount
     * @param mixed[]         $arguments
     * @return array
     */
    public function beforeRenderAmount(
        PriceBox $subject,
        AmountInterface $amount,
        array $arguments = []
    ) {
        $arguments['schema'] = false;

        return [$amount, $arguments];
    }
}
