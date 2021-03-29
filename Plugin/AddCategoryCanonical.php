<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Controller\Category\View;
use Magento\Catalog\Helper\Category as CategoryHelper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Magento\Theme\Block\Html\Pager;
use Web200\Seo\Provider\CanonicalConfig;

/**
 * Class AddCategoryCanonical
 *
 * @package   Web200\Seo\Plugin
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class AddCategoryCanonical
{
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Url builder
     *
     * @var UrlInterface $urlBuilder
     */
    protected $urlBuilder;
    /**
     * Category helper
     *
     * @var CategoryHelper $categoryHelper
     */
    protected $categoryHelper;
    /**
     * Canonical config
     *
     * @var CanonicalConfig $canonicalConfig
     */
    protected $canonicalConfig;

    /**
     * AddCanonicalToCategories constructor.
     *
     * @param CanonicalConfig $canonicalConfig
     * @param CategoryHelper  $categoryHelper
     * @param PageConfig      $pageConfig
     * @param UrlInterface    $urlBuilder
     */
    public function __construct(
        CanonicalConfig $canonicalConfig,
        CategoryHelper $categoryHelper,
        PageConfig $pageConfig,
        UrlInterface $urlBuilder
    ) {
        $this->pageConfig      = $pageConfig;
        $this->urlBuilder      = $urlBuilder;
        $this->categoryHelper  = $categoryHelper;
        $this->canonicalConfig = $canonicalConfig;
    }

    /**
     * Execute
     *
     * @param View $subject
     * @param Page $page
     *
     * @return mixed
     */
    public function afterExecute(View $subject, $page)
    {
        if (!$this->canonicalConfig->isRelPagination()) {
            return $page;
        }

        /** @var ListProduct $productListBlock */
        $productListBlock = $page->getLayout()->getBlock('category.products.list');

        /** @var Toolbar $toolbarBlock */
        $toolbarBlock = $productListBlock->getToolbarBlock();
        /** @var Pager $pagerBlock */
        $pagerBlock = $toolbarBlock->getChildBlock('product_list_toolbar_pager');
        if (!$pagerBlock) {
            return $page;
        }

        $category = $productListBlock->getLayer()->getCurrentCategory();
        $pagerBlock->setAvailableLimit($toolbarBlock->getAvailableLimit())
            ->setCollection($productListBlock->getLayer()->getProductCollection());

        if ($this->categoryHelper->canUseCanonicalTag()) {
            $this->pageConfig->getAssetCollection()->remove($category->getUrl());
        }

        $this->pageConfig->addRemotePageAsset(
            $this->getPageUrl($pagerBlock->getPageVarName(), $pagerBlock->getCurrentPage()),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        if ($pagerBlock->getCurrentPage() > 1) {
            $this->pageConfig->addRemotePageAsset(
                $this->getPageUrl($pagerBlock->getPageVarName(), $pagerBlock->getCollection()->getCurPage(-1)),
                'link_rel',
                ['attributes' => ['rel' => 'prev']]
            );
        }
        if ($pagerBlock->getCurrentPage() < $pagerBlock->getLastPageNum()) {
            $this->pageConfig->addRemotePageAsset(
                $this->getPageUrl($pagerBlock->getPageVarName(), $pagerBlock->getCollection()->getCurPage(+1)),
                'link_rel',
                ['attributes' => ['rel' => 'next']]
            );
        }

        return $page;
    }

    /**
     * Retrieve page URL by defined parameters
     *
     * @param string $varName
     * @param int    $page
     *
     * @return string
     */
    protected function getPageUrl(string $varName, int $page): string
    {
        $urlParams                 = [];
        $urlParams['_current']     = false;
        $urlParams['_escape']      = true;
        $urlParams['_use_rewrite'] = true;
        if ($page > 1) {
            $urlParams['_query'] = [$varName => $page];
        }

        return $this->urlBuilder->getUrl('*/*/*', $urlParams);
    }
}
