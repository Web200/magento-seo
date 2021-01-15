<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class TwitterCardConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class TwitterCardConfig
{
    /**
     * Type
     *
     * @var string TYPE
     */
    protected const TYPE = 'seo/twitter_card/type';
    /**
     * Site
     *
     * @var string SITE
     */
    protected const SITE = 'seo/twitter_card/site';
    /**
     * Creator
     *
     * @var string CREATOR
     */
    protected const CREATOR = 'seo/twitter_card/creator';
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
     * Get google site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getDefaultTwitterCardType($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::TYPE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get bing site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getDefaultTwitterCardSite($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::SITE, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Get pinterest site verification code
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getDefaultTwitterCardCreator($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::CREATOR, ScopeInterface::SCOPE_STORES, $store);
    }
}
