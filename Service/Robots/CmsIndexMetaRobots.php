<?php

declare(strict_types=1);

namespace Web200\Seo\Service\Robots;

use Web200\Seo\Api\CmsIndexMetaRobotsInterface;
use Magento\Cms\Model\PageRepository;
use Web200\Seo\Provider\MetaRobotsConfig;
use Magento\Store\Model\Store;
use Magento\Cms\Helper\Page as HelperPage;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


/**
 * Class CmsMetaRobots
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CmsIndexMetaRobots implements CmsIndexMetaRobotsInterface
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
   * Scope config interface
   *
   * @var ScopeConfigInterface $scopeConfig
   */
  protected $scopeConfig;

  /**
   * Cms constructor.
   * @param PageRepository $pageRepository
   * @param MetaRobotsConfig $metaRobotsConfig
   * @param ScopeConfigInterface $scopeConfig
   */
  public function __construct(
    PageRepository $pageRepository,
    MetaRobotsConfig $metaRobotsConfig,
    ScopeConfigInterface $scopeConfig
  ) {
    $this->pageRepository = $pageRepository;
    $this->metaRobotsConfig = $metaRobotsConfig;
    $this->scopeConfig = $scopeConfig;
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
    $pageId = $this->scopeConfig->getValue(HelperPage::XML_PATH_HOME_PAGE, ScopeInterface::SCOPE_STORE);

    $metaRobots = $this->metaRobotsConfig->getDefaultRobots();
    $cmsPage = $this->pageRepository->getById($pageId);

    $metaRobots = (string) $cmsPage->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $cmsPage->getMetaRobots();

    return $metaRobots;
  }
}
