<?php

declare(strict_types=1);

namespace Web200\Seo\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Breadcrumbs as BaseBreadcrumbs;

/**
 * Class Breadcrumbs
 *
 * @package   Web200\Seo\Block\Catalog\Product
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Breadcrumbs extends BaseBreadcrumbs
{
    /**
     * Product
     *
     * @var Product $product
     */
    protected $product;
    /**
     * productRepository
     *
     * @var ProductRepositoryInterface $productRepository
     */
    protected $productRepository;

    /**
     * Breadcrumbs constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Template\Context           $context
     * @param mixed[]                    $data
     * @param Json|null                  $serializer
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Template\Context $context,
        array $data = [],
        Json $serializer = null
    ) {
        parent::__construct($context, $data, $serializer);

        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link'  => $this->_storeManager->getStore()->getBaseUrl()
            ]
        );

        /** @var string $path */
        $path = $this->getBreadcrumbPath();
        /** @var string $name */
        /** @var string[] $breadcrumb */
        foreach ($path as $name => $breadcrumb) {
            $this->addCrumb($name, $breadcrumb);
            $title[] = $breadcrumb['label'];
        }
    }

    /**
     * Get breadcrumb path
     *
     * @return array
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getBreadcrumbPath(): array
    {
        /** @var string[] $path */
        $path = [];
        /** @var Product $product */
        $product = $this->getProduct();
        if (!$product) {
            return [];
        }

        /** @var Category[] $categories */
        $categories = $this->getProduct()->getCategoryCollection()
            ->addAttributeToSelect('name')
            ->setStore($this->_storeManager->getStore())
            ->addAttributeToFilter('is_active', '1')
            ->getItems();

        /** @var string $rootCategoryId */
        $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
        /** @var Category $finalCategory */
        $finalCategory = null;
        /** @var Category $category */
        foreach ($categories as $category) {
            //first loop
            if (!preg_match('/^1\/' . $rootCategoryId . '\//', $category->getPath())) {
                continue;
            }
            if (null === $finalCategory) {
                $finalCategory = $category;
            }
            if ($finalCategory->getLevel() < $category->getLevel()) {
                //recover the most refined category
                $finalCategory = $category;
            }
        }

        if ($finalCategory) {
            /** @var Category[] $parentCategories */
            $parentCategories = $finalCategory->getParentCategories();

            //foreach parentCategory from this category
            foreach ($parentCategories as $parentCategory) {
                // add parent category path breadcrumb
                $path['category' . $parentCategory->getId()] = [
                    'label' => $parentCategory->getName(),
                    'link'  => $parentCategory->getUrl()
                ];
            }
            // add last category path breadcrumb
            $path['category' . $finalCategory->getId()] = [
                'label' => $finalCategory->getName(),
                'link'  => $finalCategory->getUrl()
            ];
        }

        return $path;
    }

    /**
     * Get Product
     *
     * @return Product
     */
    public function getProduct(): ?Product
    {
        if (!$this->product) {
            try {
                $this->product = $this->productRepository->getById($this->_request->getParam('id'));
            } catch (NoSuchEntityException $noSuchEntityException) {
            }
        }

        return $this->product;
    }
}
