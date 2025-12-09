<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SiteVerificationConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class SiteVerificationConfig
{
    /**
     * SITEMAP_VERIFICATION_ACTIVE
     *
     * @var bool SITEMAP_VERIFICATION_ACTIVE
     */
    protected const SITEMAP_VERIFICATION_ACTIVE = 'seo/verifications/active';
    /**
     * Google
     *
     * @var string GOOGLE
     */
    protected const GOOGLE = 'seo/verifications/google';
    /**
     * Bing
     *
     * @var string BING
     */
    protected const BING = 'seo/verifications/bing';
    /**
     * Pinterest
     *
     * @var string PINTEREST
     */
    protected const PINTEREST = 'seo/verifications/pinterest';
    /**
     * Yandex
     *
     * @var string YANDEX
     */
    protected const YANDEX = 'seo/verifications/yandex';
    /**
     * Scope config
     *
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * SiteVerificationConfig constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is sitemap verification active
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::SITEMAP_VERIFICATION_ACTIVE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get google site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getGoogleSiteVerificationCode($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::GOOGLE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get bing site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getBingSiteVerificationCode($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::BING, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get pinterest site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getPinterestSiteVerificationCode($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::PINTEREST, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get Yandex site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getYandexSiteVerificationCode($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::YANDEX, ScopeInterface::SCOPE_STORES, $store);
    }
}
