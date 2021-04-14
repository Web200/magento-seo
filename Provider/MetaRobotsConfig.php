<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class MetaRobotsConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class MetaRobotsConfig
{
    /**
     * Default robots
     *
     * @var string DEFAULT_ROBOTS
     */
    protected const DEFAULT_ROBOTS = 'design/search_engine_robots/default_robots';
    /**
     * Category noindex active
     *
     * @var string CATEGORY_NOINDEX_ACTIVE
     */
    protected const CATEGORY_NOINDEX_ACTIVE = 'seo/meta_robots/category_noindex_active';
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
     * Get default robots
     *
     * @param mixed $store
     *
     * @return string
     */
    public function getDefaultRobots($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::DEFAULT_ROBOTS, ScopeInterface::SCOPE_STORES, $store);
    }

    /**
     * Is category no index active
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isCategoryNoIndexActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::CATEGORY_NOINDEX_ACTIVE, ScopeInterface::SCOPE_STORES, $store);
    }
}
