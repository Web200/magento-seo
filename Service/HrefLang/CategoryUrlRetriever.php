<?php

declare(strict_types=1);

namespace Web200\Seo\Service\HrefLang;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Web200\Seo\Api\CategoryUrlRetrieverInterface;

/**
 * Class CategoryUrlRetriever
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CategoryUrlRetriever implements CategoryUrlRetrieverInterface
{
    /**
     * Category repository
     *
     * @var CategoryRepositoryInterface $categoryRepository
     */
    protected $categoryRepository;
    /**
     * Category url path generator
     *
     * @var CategoryUrlPathGenerator $categoryUrlPathGenerator
     */
    protected $categoryUrlPathGenerator;

    /**
     * CategoryUrlRetriever constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryUrlPathGenerator    $categoryUrlPathGenerator
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryUrlPathGenerator $categoryUrlPathGenerator
    ) {
        $this->categoryRepository       = $categoryRepository;
        $this->categoryUrlPathGenerator = $categoryUrlPathGenerator;
    }

    /**
     * Get url
     *
     * @param int   $identifier the category ID
     * @param Store $store
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getUrl($identifier, $store): string
    {
        /** @var Category $category */
        $category = $this->categoryRepository->get($identifier, $store->getId());

        if (!$category->getIsActive()) {
            return '';
        }
        $path = $this->categoryUrlPathGenerator->getUrlPathWithSuffix($category);

        return $store->getBaseUrl() . $path;
    }
}
