<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Locale\TranslatedLists;

/**
 * Class MicrodataConfig
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class OrganizationConfig
{

  protected RegionFactory $regionFactory;
  protected TranslatedLists $localeResolver;
  public const ORGANIZATION_CONFIG_PATHS = [
    'name' => 'general/store_information/name',
    'email' => 'trans_email/ident_general/email',
    'phone' => 'general/store_information/phone',
    'vatID' => 'general/store_information/merchant_vat_number',
    'areaServed' => 'general/country/allow',
    'availableLanguage' => 'general/locale/code',
    'streetAddress' => 'general/store_information/street_line1',
    'addressLocality' => 'general/store_information/city',
    'postalCode' => 'general/store_information/postcode',
    'addressRegion' => 'general/store_information/region_id',
    'addressCountry' => 'general/store_information/country_id'
  ];

  /**
   * Scope config
   *
   * @var ScopeConfigInterface $scopeConfig
   */
  protected $scopeConfig;

  /**
   * SitemapConfig constructor.
   *
   * @param ScopeConfigInterface $scopeConfig
   * @param RegionFactory $regionFactory
   * @param TranslatedLists $localeResolver
   */
  public function __construct(
    ScopeConfigInterface $scopeConfig,
    RegionFactory $regionFactory,
    TranslatedLists $localeResolver
  ) {
    $this->scopeConfig = $scopeConfig;
    $this->regionFactory = $regionFactory;
    $this->localeResolver = $localeResolver;
  }

  /**
   * Get category url key
   *
   * @param mixed $store
   *
   * @return string
   */
  public function getOrganizationConfigs($store = null): array
  {
    $configValues = [];

    foreach (self::ORGANIZATION_CONFIG_PATHS as $key => $path) {
      $configValues[$key] = (string) $this->scopeConfig->getValue(
        $path,
        ScopeInterface::SCOPE_STORE,
        $store
      );

      if ($key === 'addressRegion') {
        $configValues[$key] = $this->getAddressName($configValues[$key]);
      } else if ($key === 'availableLanguage') {
        $configValues[$key] = $this->getLanguageNameFromLocale($configValues[$key]);
      }
    }

    return $configValues;
  }

  public function getAddressName($regionId): string
  {
    $address = $this->regionFactory->create()->load($regionId);
    return $address->getName();
  }

  public function getLanguageNameFromLocale($localeCode): string
  {
    $languageOptions = $this->localeResolver->getOptionLocales();
    foreach ($languageOptions as $languageOption) {
      if ($languageOption['value'] == $localeCode) {
        return rtrim(preg_replace('/\([^)]+\)/', '', $languageOption['label']));
      }
    }
    return '';
  }
}
