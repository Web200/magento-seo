<?php

declare(strict_types=1);

namespace Web200\Seo\Service\Robots;

use Web200\Seo\Api\ProductMetaRobotsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Web200\Seo\Provider\MetaRobotsConfig;
use Magento\Store\Model\Store;



/**
 * Class ProductUrlRetriever
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class ProductMetaRobots implements ProductMetaRobotsInterface
{
  /**
   * Product repository
   *
   * @var ProductRepositoryInterface $productRepository
   */
  protected $productRepository;
  /**
   * Meta robots config
   *
   * @var MetaRobotsConfig $metaRobotsConfig
   */
  protected $metaRobotsConfig;

  /**
   * Product constructor.
   * @param ProductRepositoryInterface $productRepository
   * @param MetaRobotsConfig $metaRobotsConfig
   */
  public function __construct(
    ProductRepositoryInterface $productRepository,
    MetaRobotsConfig $metaRobotsConfig,
  ) {
    $this->productRepository = $productRepository;
    $this->metaRobotsConfig = $metaRobotsConfig;
  }

  /**
   * Get url
   *
   * @param int   $identifier the product ID
   * @param Store $store
   *
   * @return string
   */
  public function getMetaRobots($identifier, $store): string
  {
    $product = $this->productRepository->getById($identifier, false, $store->getId());
    $metaRobots = $this->metaRobotsConfig->getDefaultRobots();
    if ($product) {
      $metaRobots = (string) $product->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $product->getMetaRobots();
    }

    return $metaRobots;
  }
}
