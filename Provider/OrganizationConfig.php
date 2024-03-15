<?php

declare(strict_types=1);

namespace Web200\Seo\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

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
  public const ORGANIZATION_CONFIG_PATHS = [
    'name' => 'general/store_information/name',
    'email' => 'trans_email/ident_general/name',
    'phone' => 'general/store_information/phone',
    'vatID' => 'general/store_information/vat_number',
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
   */
  public function __construct(
    ScopeConfigInterface $scopeConfig
  ) {
    $this->scopeConfig = $scopeConfig;
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
    }

    return $configValues;
  }
}
