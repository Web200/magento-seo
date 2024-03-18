<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Directory\Model\RegionFactory;

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
   */
  public function __construct(
    ScopeConfigInterface $scopeConfig,
    RegionFactory $regionFactory
  ) {
    $this->scopeConfig = $scopeConfig;
    $this->regionFactory = $regionFactory;
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
      }
    }

    return $configValues;
  }

  public function getAddressName($regionId): string
  {
    $address = $this->regionFactory->create()->load($regionId);
    return $address->getName();
  }
}
