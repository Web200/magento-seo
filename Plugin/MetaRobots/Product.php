<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin\MetaRobots;

use Magento\Catalog\Controller\Product\View;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Web200\Seo\Provider\MetaRobotsConfig;

/**
 * Class Product
 *
 * @package   Web200\Seo\Plugin\MetaRobots
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Product
{
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Registry
     *
     * @var Registry $registry
     */
    protected Registry $registry;
    /**
     * Meta robots config
     *
     * @var MetaRobotsConfig $metaRobotsConfig
     */
    protected $metaRobotsConfig;

    /**
     * AddCanonicalToCategories constructor.
     *
     * @param PageConfig       $pageConfig
     * @param MetaRobotsConfig $metaRobotsConfig
     * @param Registry         $registry
     */
    public function __construct(
        PageConfig $pageConfig,
        MetaRobotsConfig $metaRobotsConfig,
        Registry $registry
    ) {
        $this->pageConfig       = $pageConfig;
        $this->registry         = $registry;
        $this->metaRobotsConfig = $metaRobotsConfig;
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
        /** @var ModelProduct $product */
        $product = $this->registry->registry('product');
        if ($product) {
            $metaRobots = (string)$product->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $product->getMetaRobots();
            $this->pageConfig->setMetadata('robots', $metaRobots);
        }

        return $page;
    }
}
