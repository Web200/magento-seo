<?php

namespace Web200\Seo\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Web200\Seo\Provider\OrganizationConfig;


class Organization implements ResolverInterface
{
  /**
   * @var OrganizationConfig $organizationConfig
   */
  protected OrganizationConfig $organizationConfig;

  /**
   * @param OrganizationConfig $organizationConfig
   */
  public function __construct(
    OrganizationConfig $organizationConfig
  ) {
    $this->organizationConfig = $organizationConfig;
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
    if (!isset($args['store_id'])) {
      throw new NoSuchEntityException(__('Store ID is required.'));
    }
    $store_id = $args['store_id'];
    return $this->organizationConfig->getOrganizationConfigs($store_id);
  }
}
