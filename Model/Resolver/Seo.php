<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Web200\Seo\Provider\CanonicalConfig;
use Web200\Seo\Provider\MetaRobotsConfig;
use Web200\Seo\Provider\MicrodataConfig;
use Web200\Seo\Provider\SitemapConfig;
use Web200\Seo\Provider\SiteVerificationConfig;
use Web200\Seo\Provider\TwitterCardConfig;
use Web200\Seo\Provider\HreflangConfig;

/**
 * Class HreflangConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Seo implements ResolverInterface
{
  /**
   * @var CanonicalConfig $canonicalConfig
   */
  protected CanonicalConfig $canonicalConfig;

  /**
   * @var MetaRobotsConfig $metaRobotsConfig
   */
  protected MetaRobotsConfig $metaRobotsConfig;

  /**
   * @var MicrodataConfig $microDataConfig
   */
  protected MicrodataConfig $microDataConfig;

  /**
   * @var SitemapConfig $siteMapConfig
   */
  protected SitemapConfig $siteMapConfig;

  /**
   * @var SiteVerificationConfig $siteVerificationConfig
   */
  protected SiteVerificationConfig $siteVerificationConfig;

  /**
   * @var TwitterCardConfig $twitterCardConfig
   */
  protected TwitterCardConfig $twitterCardConfig;

  /**
   * @var HreflangConfig $hreflangConfig
   */
  protected HreflangConfig $hreflangConfig;

  /**
   * @param CanonicalConfig $canonicalConfig
   * @param MetaRobotsConfig $metaRobotsConfig
   * @param MicrodataConfig $microDataConfig
   * @param SitemapConfig $siteMapConfig
   * @param SiteVerificationConfig $siteVerificationConfig
   * @param TwitterCardConfig $twitterCardConfig
   * @param HreflangConfig $hreflangConfig
   */
  public function __construct(
    CanonicalConfig $canonicalConfig,
    MetaRobotsConfig $metaRobotsConfig,
    MicrodataConfig $microDataConfig,
    SitemapConfig $siteMapConfig,
    SiteVerificationConfig $siteVerificationConfig,
    TwitterCardConfig $twitterCardConfig,
    HreflangConfig $hreflangConfig
  ) {
    $this->canonicalConfig = $canonicalConfig;
    $this->metaRobotsConfig = $metaRobotsConfig;
    $this->microDataConfig = $microDataConfig;
    $this->siteMapConfig = $siteMapConfig;
    $this->siteVerificationConfig = $siteVerificationConfig;
    $this->twitterCardConfig = $twitterCardConfig;
    $this->hreflangConfig = $hreflangConfig;
  }

  /**
   * @param Field $field
   * @param $context
   * @param ResolveInfo $info
   * @param array|null $value
   * @param array|null $args
   * @return array|null[]
   * @throws GraphQlInputException
   */
  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
  {
    return [
      'canonical' => $this->getCanonicalConfigurations(),
      'metaRobots' => $this->getMetaRobotsConfigurations(),
      'microData' => $this->getMicroDataConfigurations(),
      'siteMap' => $this->getSiteMapConfigurations(),
      'siteVerification' => $this->getSiteVerificationConfiguration(),
      'twitterCard' => $this->getTwitterCardConfigurations(),
      'hreflang' => $this->getHreflangConfigurations()
    ];
  }

  public function getMetaRobotsConfigurations(): array
  {
    return [
      'isNoIndexActive' => $this->metaRobotsConfig->isCategoryNoIndexActive(),
      'defaultRobots' => $this->metaRobotsConfig->getDefaultRobots()
    ];
  }

  public function getCanonicalConfigurations(): array
  {
    return [
      'isRelPagination' => $this->canonicalConfig->isRelPagination(),
      'isCmsActive' => $this->canonicalConfig->isCmsActive(),
      'isSimpleProductSitemap' => $this->canonicalConfig->isSimpleProductSitemap(),
      'isStorePreferenceEnable' => $this->canonicalConfig->isStorePreferenceEnable()
    ];
  }

  public function getMicroDataConfigurations(): array
  {
    return [
      'brand' => $this->microDataConfig->getBrand(),
      'isActive' => $this->microDataConfig->isActive()
    ];
  }

  public function getSiteMapConfigurations(): array
  {
    return [
      'isCategoryActive' => $this->siteMapConfig->isCategoryActive(),
      'categoryUrlKey' => $this->siteMapConfig->getCategoryUrlKey(),
      'maxDepth' => $this->siteMapConfig->getMaxDepth()
    ];
  }

  public function getSiteVerificationConfiguration(): array
  {
    return [
      'isActive' => $this->siteVerificationConfig->isActive(),
      'googleVerificationCode' => $this->siteVerificationConfig->getGoogleSiteVerificationCode(),
      'bingVerificationCode' => $this->siteVerificationConfig->getBingSiteVerificationCode(),
      'pintrestVerificationCode' => $this->siteVerificationConfig->getPinterestSiteVerificationCode(),
      'yandexVerificationCode' => $this->siteVerificationConfig->getYandexSiteVerificationCode()
    ];
  }

  public function getTwitterCardConfigurations(): array
  {
    return [
      'isActive' => $this->twitterCardConfig->isActive(),
      'defaultCardType' => $this->twitterCardConfig->getDefaultTwitterCardType(),
      'defaultSiteUsername' => $this->twitterCardConfig->getDefaultTwitterCardSite(),
      'defaultCreator' => $this->twitterCardConfig->getDefaultTwitterCardCreator()
    ];
  }

  public function getHreflangConfigurations(): array
  {
    return [
      'isActive' => $this->hreflangConfig->isActive(),
      'localeCode' => $this->hreflangConfig->getLocaleCode(),
      'useSameWebsiteOnly' => $this->hreflangConfig->useSameWebsiteOnly()
    ];
  }
}
