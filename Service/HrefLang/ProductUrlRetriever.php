<?php

declare(strict_types=1);

namespace Web200\Seo\Service\HrefLang;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Web200\Seo\Api\ProductUrlRetrieverInterface;

/**
 * Class ProductUrlRetriever
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class ProductUrlRetriever implements ProductUrlRetrieverInterface
{
    /**
     * Product repository
     *
     * @var ProductRepositoryInterface $productRepository
     */
    protected $productRepository;
    /**
     * Product url path generator
     *
     * @var ProductUrlPathGenerator $productUrlPathGenerator
     */
    protected $productUrlPathGenerator;

    /**
     * ProductUrlRetriever constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param ProductUrlPathGenerator    $productUrlPathGenerator
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductUrlPathGenerator $productUrlPathGenerator
    ) {
        $this->productRepository = $productRepository;
        $this->productUrlPathGenerator = $productUrlPathGenerator;
    }

    /**
     * Get url
     *
     * @param int   $identifier the product ID
     * @param Store $store
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getUrl($identifier, $store): string
    {
        /** @var Product $product */
        $product = $this->productRepository->getById($identifier, false, $store->getId());
        $websiteId = $store->getWebsiteId();
        $associatedWebsiteIds = $product->getWebsiteIds();

        if ($product->isDisabled() || !in_array($websiteId, $associatedWebsiteIds)) {
            return '';
        }
        $path = $this->productUrlPathGenerator->getUrlPathWithSuffix($product, $store->getId());

        return $store->getBaseUrl() . $path;
    }
}
