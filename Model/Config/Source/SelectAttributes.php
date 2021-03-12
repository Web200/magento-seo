<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class SelectAttributes
 *
 * @package   Web200\Seo\Model\Config\Source
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class SelectAttributes implements OptionSourceInterface
{
    /**
     * Collection
     *
     * @var Collection $collection
     */
    protected $collection;

    /**
     * SelectAttributes constructor.
     *
     * @param Collection $collection
     */
    public function __construct(
        Collection $collection
    ) {
        $this->collection = $collection;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        $this->collection
            ->addFieldToFilter(Set::KEY_ENTITY_TYPE_ID, 4)
            ->addFieldToFilter('frontend_input', 'select');
        $attrAll = $this->collection->load()->getItems();
        /** @var mixed[] $array */
        $array = [];
        $array[] = ['value' => '', 'label' => ' '];
        foreach ($attrAll as $attribute) {
            $array[] = [
                'value' => $attribute['attribute_code'],
                'label' => $attribute['frontend_label'] . ' (' . $attribute['attribute_code'] . ')'
            ];
        }

        return $array;
    }
}
