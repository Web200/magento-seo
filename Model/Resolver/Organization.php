<?php

namespace Web200\Seo\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;
use Web200\Seo\Provider\OrganizationConfig;



class Organization implements ResolverInterface
{
  /**
   * @var OrganizationConfig $organizationConfig
   */
  protected OrganizationConfig $organizationConfig;

  protected $storeManager;


  /**
   * @param OrganizationConfig $organizationConfig
   * @param StoreManagerInterface $storeManager
   */
  public function __construct(
    OrganizationConfig $organizationConfig,
    StoreManagerInterface $storeManager

  ) {
    $this->organizationConfig = $organizationConfig;
    $this->storeManager = $storeManager;

  }

  /**
   * Resolve method
   *
   * @param Field $field
   * @param array $context
   * @param ResolveInfo $info
   * @param array|null $value
   * @param array|null $args
   * @return mixed
   * @throws NoSuchEntityException
   */
  public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
  {
    if (!isset ($args['store_code'])) {
      throw new NoSuchEntityException(__('Store Code is required.'));
    }
    $store_id = $this->storeManager->getStore($args['store_code'])->getId(); //$args['store_id'];
    return $this->organizationConfig->getOrganizationConfigs($store_id);
  }
}
