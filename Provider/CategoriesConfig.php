<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class CategoriesConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CategoriesConfig
{
    /**
     * Add rel pagination
     *
     * @var string ADD_REL_PAGINATION
     */
    protected const ADD_REL_PAGINATION = 'seo/categories/add_rel_pagination';
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
}
