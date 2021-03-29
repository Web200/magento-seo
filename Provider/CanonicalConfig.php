<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class CanonicalConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CanonicalConfig
{
    /**
     * Add rel pagination
     *
     * @var string ADD_REL_PAGINATION
     */
    protected const ADD_REL_PAGINATION = 'seo/canonical/add_rel_pagination';
    /**
     * CMS
     *
     * @var string CMS
     */
    protected const CMS = 'seo/canonical/cms';
    /**
     * Scope config
     *
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * CanonicalConfig constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is rel pagination
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isRelPagination($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::ADD_REL_PAGINATION, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Is cms active
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isCmsActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::CMS, ScopeInterface::SCOPE_STORES, $store);
    }
}
