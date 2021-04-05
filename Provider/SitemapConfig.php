<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SitemapConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class SitemapConfig
{
    /**
     * Category Active
     *
     * @var string CATEGORY_ACTIVE
     */
    protected const CATEGORY_ACTIVE = 'seo/html_sitemap/category/active';
    /**
     * Max depth
     *
     * @var string MAX_DEPTH
     */
    protected const MAX_DEPTH = 'seo/html_sitemap/category/max_depth';
    /**
     * Category Url key
     *
     * @var string CATEGORY_URL_KEY
     */
    protected const CATEGORY_URL_KEY = 'seo/html_sitemap/category/url_key';
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
     * Is category active
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isCategoryActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::CATEGORY_ACTIVE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get category url key
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getCategoryUrlKey($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::CATEGORY_URL_KEY, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get max depth
     *
     * @param mixed $store
     *
     * @return int
     */
    public function getMaxDepth($store = null): int
    {
        return (int)$this->scopeConfig->getValue(self::MAX_DEPTH, ScopeInterface::SCOPE_STORES, $store);
    }
}
