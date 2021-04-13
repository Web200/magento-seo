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
     * @return bool
     */
    public function getDefaultRobots($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::DEFAULT_ROBOTS, ScopeInterface::SCOPE_STORES, $store);
    }
}
