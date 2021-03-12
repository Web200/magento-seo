<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class MicrodataConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class MicrodataConfig
{
    /**
     * Microdata brand
     *
     * @var string MICRODATA_BRAND
     */
    protected const MICRODATA_BRAND = 'seo/microdata/brand';
    /**
     * Scope config
     *
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * SitemapConfig constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get category url key
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getBrand($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::MICRODATA_BRAND, ScopeInterface::SCOPE_STORES, $store);
    }
}
