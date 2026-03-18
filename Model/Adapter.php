<?php

declare(strict_types=1);

namespace Web200\Seo\Model;

use Web200\Seo\Api\Data\AdapterInterface;
use Web200\Seo\Api\Data\PropertyInterface;

/**
 * Class Adapter
 *
 * @package   Web200\Seo\Model
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Adapter implements AdapterInterface
{
    /**
     * Adapter constructor.
     *
     * @param PropertyInterface $property
     * @param mixed[]           $adapters
     */
    public function __construct(
        protected PropertyInterface $property,
        protected array $adapters
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getProperty(): PropertyInterface
    {
        /** @var AdapterInterface $item */
        foreach ($this->adapters as $item) {
            /** @var Property $property */
            $property = $item->getProperty();
            if ($property->hasData()) {
                return $property;
            }
        }

        return $this->property;
    }
}
