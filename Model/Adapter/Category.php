<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Adapter;

use Magento\Catalog\Model\Category as ModelCategory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Web200\Seo\Api\Data\AdapterInterface;
use Web200\Seo\Api\Data\PropertyInterface;
use Web200\Seo\Model\BlockParser;
use Web200\Seo\Model\Property;

/**
 * Class Category
 *
 * @package   Web200\Seo\Model\Adapter
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Category implements AdapterInterface
{
    /**
     * Message attributes
     *
     * @var string[] $messageAttributes
     */
    protected $messageAttributes = ['meta_description', 'description'];
    /**
     * Registry
     *
     * @var Registry $registry
     */
    protected $registry;
    /**
     * Property interface
     *
     * @var PropertyInterface $property
     */
    protected $property;
    /**
     * Block parser
     *
     * @var BlockParser $blockParser
     */
    protected $blockParser;

    /**
     * Category constructor.
     *
     * @param PropertyInterface $property
     * @param BlockParser       $blockParser
     * @param Registry          $registry
     */
    public function __construct(
        PropertyInterface $property,
        BlockParser $blockParser,
        Registry $registry
    ) {
        $this->registry    = $registry;
        $this->property    = $property;
        $this->blockParser = $blockParser;
    }

    /**
     * Get property
     *
     * @return PropertyInterface
     * @throws LocalizedException
     */
    public function getProperty(): PropertyInterface
    {
        /** @var ModelCategory $category */
        $category = $this->registry->registry('current_category');
        if ($category) {
            $this->property->setTitle((string)$category->getName());
            $this->property->setUrl((string)$category->getUrl());

            foreach ($this->messageAttributes as $messageAttribute) {
                if ($category->getData($messageAttribute)) {
                    $this->property->setDescription($category->getData($messageAttribute));
                }
            }

            if ($category->hasLandingPage() && !$this->property->getProperty('description')) {
                $this->property->setDescription(
                    $this->blockParser->getBlockContentById((int)$category->getLandingPage())
                );
            }

            if ($category->getImageUrl()) {
                $this->property->setImage((string)$category->getImageUrl());
            }
            $this->property->addProperty('item', $category->getData(), Property::META_DATA_GROUP);
        }

        return $this->property;
    }
}
