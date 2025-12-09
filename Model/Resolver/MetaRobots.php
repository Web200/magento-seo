<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Store\Model\StoreManagerInterface;
use Web200\Seo\Service\Robots\MetaRobots as MetaRobotsFinder;


/**
 * Class MetaRobots
 *
 * @package   Web200\Seo\Provider
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class MetaRobots implements ResolverInterface
{
  /**
   * @var StoreManagerInterface
   */
  protected $_storeManager;

  /**
   * Alternative url service
   *
   * @var MetaRobotsFinder $metaRobotsFinder
   */
  protected $metaRobotsFinder;

  /**
   * @param  StoreManagerInterface $storeManager
   * @param MetaRobotsFinder $metaRobotsFinder
   */
  public function __construct(
    StoreManagerInterface $storeManager,
    MetaRobotsFinder $metaRobotsFinder,
  ) {
    $this->_storeManager = $storeManager;
    $this->metaRobotsFinder = $metaRobotsFinder;
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

    return [
      'robots' => $this->metaRobotsFinder->getEntityMetaRobots($this->_storeManager->getStore(), $id, $action)
    ];
  }
}
