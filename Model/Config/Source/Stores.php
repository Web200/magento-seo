<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Stores
 *
 * @package   Web200\Seo\Model\Config\Source
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Stores implements OptionSourceInterface
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $final  = [];
        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            $final[] = [
                'value' => $store->getId(),
                'label' => $store->getName()
            ];
        }

        return $final;
    }
}
