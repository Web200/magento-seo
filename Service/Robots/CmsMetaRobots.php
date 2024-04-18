<?php

declare(strict_types=1);

namespace Web200\Seo\Service\Robots;

use Web200\Seo\Api\CmsMetaRobotsInterface;
use Magento\Cms\Model\PageRepository;
use Web200\Seo\Provider\MetaRobotsConfig;
use Magento\Store\Model\Store;



/**
 * Class CmsMetaRobots
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CmsMetaRobots implements CmsMetaRobotsInterface
{
  /**
   * page repository
   *
   * @var PageRepository $pageRepository
   */
  protected $pageRepository;
  /**
   * Meta robots config
   *
   * @var MetaRobotsConfig $metaRobotsConfig
   */
  protected $metaRobotsConfig;

  /**
   * Cms constructor.
   * @param PageRepository $pageRepository
   * @param MetaRobotsConfig $metaRobotsConfig
   */
  public function __construct(
    PageRepository $pageRepository,
    MetaRobotsConfig $metaRobotsConfig,
  ) {
    $this->pageRepository = $pageRepository;
    $this->metaRobotsConfig = $metaRobotsConfig;
  }

  /**
   * Get url
   *
   * @param int   $identifier the cms ID
   * @param Store $store
   *
   * @return string
   */
  public function getMetaRobots($identifier, $store): string
  {
    $metaRobots = $this->metaRobotsConfig->getDefaultRobots();
    $cmsPage = $this->pageRepository->getById($identifier);

    $metaRobots = (string) $cmsPage->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $cmsPage->getMetaRobots();

    return $metaRobots;
  }
}
