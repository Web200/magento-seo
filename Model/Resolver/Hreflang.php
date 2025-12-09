<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\Group;
use Magento\Store\Model\Store;
use Magento\Store\Model\Website;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Web200\Seo\Service\HrefLang\AlternativeUrlService;


/**
 * Class Hreflang
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Hreflang implements ResolverInterface
{
  /**
   * @var StoreManagerInterface
   */
  protected $_storeManager;

  /**
   * Alternative url service
   *
   * @var AlternativeUrlService $alternativeUrlService
   */
  protected $alternativeUrlService;

  /**
   * Scope config
   *
   * @var ScopeConfigInterface $scopeConfig
   */
  protected $scopeConfig;

  /**
   * @param  StoreManagerInterface $storeManager
   * @param AlternativeUrlService $alternativeUrlService
   * @param ScopeConfigInterface $scopeConfig
   */
  public function __construct(
    StoreManagerInterface $storeManager,
    AlternativeUrlService $alternativeUrlService,
    ScopeConfigInterface $scopeConfig
  ) {
    $this->_storeManager = $storeManager;
    $this->alternativeUrlService = $alternativeUrlService;
    $this->scopeConfig = $scopeConfig;
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
    if (!isset($args['action']) || !isset($args['id'])) {
      throw new GraphQlInputException(__('Both "action" and "id" arguments are required.'));
    }

    $id = $args['id'];
    $action = $args['action'];

    return $this->getAlternates($id, $action);
  }

  public function getAlternates($id, $action): array
  {
    /** @var string[] $data */
    $data = [];
    /** @var Store $store */
    foreach ($this->getStores() as $store) {
      /** @var string $url */
      $url = $this->getStoreUrl($store, $id, $action);

      if ($url) {
        // $data[$this->getLocaleCode($store)] = $url;
        $data[] = ['locale' => $this->getLocaleCode($store), 'url' => $url];
      }
    }

    return $data;
  }
  protected function getStoreUrl(Store $store, $id, $action): string
  {
    return $this->alternativeUrlService->getAlternativeUrl($store, $id, $action);
  }

  private function getLocaleCode(Store $store): string
  {
    /** @var string $localeCode */
    $localeCode = $this->scopeConfig->getValue('seo/hreflang/locale_code', 'stores', $store->getId())
      ?: $this->scopeConfig->getValue('general/locale/code', 'stores', $store->getId());
    ;

    return str_replace('_', '-', strtolower($localeCode));
  }

  protected function getStores(): array
  {
    if ($this->scopeConfig->isSetFlag('seo/hreflang/same_website_only')) {
      return $this->getSameWebsiteStores();
    }

    return $this->_storeManager->getStores();
  }

  protected function getSameWebsiteStores(): array
  {
    /** @var string[] $stores */
    $stores = [];
    /** @var Website $website */
    $website = $this->_storeManager->getWebsite();
    /** @var Store[] $group */
    foreach ($website->getGroups() as $group) {
      /** @var Group $group */
      foreach ($group->getStores() as $store) {
        $stores[] = $store;
      }
    }

    return $stores;
  }
}