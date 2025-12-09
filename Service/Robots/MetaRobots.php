<?php

declare(strict_types=1);

namespace Web200\Seo\Service\Robots;

use Magento\Store\Model\Store;
use Web200\Seo\Api\ProductMetaRobotsInterface;
use Web200\Seo\Api\CmsMetaRobotsInterface;
use Web200\Seo\Api\CmsIndexMetaRobotsInterface;

/**
 * Class AlternativeUrlService
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class MetaRobots
{
  /**
   * Cms page url retriver interface
   *
   * @var CmsMetaRobotsInterface $cmsMetaRobots
   */
  protected $cmsMetaRobots;
  /**
   * Cms page url retriver interface
   *
   * @var CmsIndexMetaRobotsInterface $cmsIndexMetaRobots
   */
  protected $cmsIndexMetaRobots;
  /**
   * Product url retriver interface
   *
   * @var ProductMetaRobotsInterface $productMetaRobots
   */
  protected $productMetaRobots;

  /**
   * AlternativeUrlService constructor.
   *
   * @param CmsMetaRobotsInterface  $cmsMetaRobots
   * @param ProductMetaRobotsInterface  $productMetaRobots
   * @param CmsIndexMetaRobotsInterface $cmsIndexMetaRobots
   */
  public function __construct(
    CmsMetaRobotsInterface $cmsMetaRobots,
    ProductMetaRobotsInterface $productMetaRobots,
    CmsIndexMetaRobotsInterface $cmsIndexMetaRobots
  ) {
    $this->cmsMetaRobots = $cmsMetaRobots;
    $this->productMetaRobots = $productMetaRobots;
    $this->cmsIndexMetaRobots = $cmsIndexMetaRobots;
  }

  /**
   * Get alternative url
   *
   * @param Store $store
   *
   * @return string
   */
  public function getEntityMetaRobots($store, $id, $action): string
  {
    $metaRobots = '';
    switch ($action) {
      case 'product':
        $metaRobots = $this->productMetaRobots->getMetaRobots($id, $store);
        break;
      case 'cms':
        $metaRobots = $this->cmsMetaRobots->getMetaRobots($id, $store);
        break;
      case 'cms_index':
        $metaRobots = $this->cmsIndexMetaRobots->getMetaRobots($id, $store);
        break;
    }

    return $metaRobots;
  }
}
