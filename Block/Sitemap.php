<?php

declare(strict_types=1);

namespace Web200\Seo\Block;

use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Catalog\Model\Resource\Category\Collection;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection as TreeNodeCollection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Web200\Seo\Provider\SitemapConfig;

/**
 * Class Sitemap
 *
 * @package   Web200\Seo\Block
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Sitemap extends Template
{
    /**
     * Category helper
     *
     * @var CategoryHelper $categoryHelper
     */
    protected $categoryHelper;
    /**
     * Category flat config
     *
     * @var State $categoryFlatConfig
     */
    protected $categoryFlatConfig;
    /**
     * Sitemap config
     *
     * @var SitemapConfig $sitemapConfig
     */
    protected $sitemapConfig;

    /**
     * Sitemap constructor.
     *
     * @param Context        $context
     * @param CategoryHelper $categoryHelper
     * @param State          $categoryFlatState
     * @param SitemapConfig  $sitemapConfig
     * @param mixed[]        $data
     */
    public function __construct(
        Context $context,
        CategoryHelper $categoryHelper,
        State $categoryFlatState,
        SitemapConfig $sitemapConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->categoryHelper     = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->sitemapConfig      = $sitemapConfig;
    }

    /**
     * @inheritdoc
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Get category helper
     *
     * @return CategoryHelper
     */
    public function getCategoryHelper(): CategoryHelper
    {
        return $this->categoryHelper;
    }

    /**
     * Retrieve current store categories
     *
     * @param bool|string $sorted
     * @param bool        $asCollection
     * @param bool        $toLoad
     *
     * @return TreeNodeCollection|Collection|mixed[]
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->getCategoryHelper()->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /**
     * Get child categories
     *
     * @param Node $category
     *
     * @return TreeNodeCollection
     */
    public function getChildCategories(Node $category): TreeNodeCollection
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }

        return $subcategories;
    }

    /**
     * Get child categories
     *
     * @param Node $category
     *
     * @return string
     */
    public function getBlockChildCategories(Node $category): string
    {
        if ($category->getLevel() >= ($this->sitemapConfig->getMaxDepth() + 1)) {
            return '';
        }

        return $this->getLayout()->createBlock(Sitemap::class)
            ->setData('category', $category)
            ->setTemplate('Web200_Seo::sitemap/children_category.phtml')
            ->toHtml();
    }
}
