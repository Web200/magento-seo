<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class HreflangConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class HreflangConfig
{
    /**
     * HREFLANG_ACTIVE
     *
     * @var string HREFLANG_ACTIVE
     */
    protected const HREFLANG_ACTIVE = 'seo/hreflang/active';
    /**
     * HREFLANG_SAME_WEBSITE_ONLY active
     *
     * @var string HREFLANG_SAME_WEBSITE_ONLY
     */
    protected const HREFLANG_SAME_WEBSITE_ONLY = 'seo/hreflang/same_website_only';
    /**
     * HREFLANG_LOCALE_CODE
     *
     * @var string HREFLANG_LOCALE_CODE
     */
    protected const HREFLANG_LOCALE_CODE = 'seo/hreflang/locale_code';
    /**
     * Scope config
     *
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * HreflangConfig constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get hreflang locale code.
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getLocaleCode($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::HREFLANG_LOCALE_CODE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Is hreflang tags used for stores from the same website only.
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function useSameWebsiteOnly($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::HREFLANG_SAME_WEBSITE_ONLY, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Is hreflang active.
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::HREFLANG_ACTIVE, ScopeInterface::SCOPE_STORES, $store);
    }
}
