<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Adapter;

use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Framework\Registry;
use Web200\Seo\Api\Data\AdapterInterface;
use Web200\Seo\Api\Data\PropertyInterface;
use Web200\Seo\Model\Property;

/**
 * Class Product
 *
 * @package   Web200\Seo\Model\Adapter
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Product implements AdapterInterface
{
    /**
     * Message attributes
     *
     * @var string[] $messageAttributes
     */
    protected $messageAttributes = [
        'meta_description',
        'short_description',
        'description'
    ];
    /**
     * Property interface
     *
     * @var PropertyInterface $property
     */
    protected $property;
    /**
     * Registry
     *
     * @var Registry $registry
     */
    protected $registry;
    /**
     * Image builder
     *
     * @var ImageBuilder $imageBuilder
     */
    protected $imageBuilder;

    /**
     * Product constructor.
     *
     * @param PropertyInterface $property
     * @param ImageBuilder      $imageBuilder
     * @param Registry          $registry
     */
    public function __construct(
        PropertyInterface $property,
        ImageBuilder $imageBuilder,
        Registry $registry
    ) {
        $this->property     = $property;
        $this->registry     = $registry;
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * Get property
     *
     * @return PropertyInterface
     */
    public function getProperty(): PropertyInterface
    {
        $product = $this->registry->registry('current_product');
        if ($product) {
            $this->property->addProperty('og:type', 'og:product', 'product');
            $this->property->setTitle((string)$product->getName());

            foreach ($this->messageAttributes as $messageAttribute) {
                if ($product->getData($messageAttribute)) {
                    $this->property->setDescription((string)$product->getData($messageAttribute));
                }
            }
            if ($product->getImage()) {
                $this->property->setImage((string)$this->getImage($product, 'product_base_image')->getImageUrl());
            }
            $this->property->setUrl($product->getProductUrl());
            $this->property->addProperty(
                'product:price:amount',
                (string)round($product->getFinalPrice(), 2),
                'product'
            );
            $this->property->addProperty('item', $product->getData(), Property::META_DATA_GROUP);
        }

        return $this->property;
    }

    /**
     * Get image
     *
     * @param ModelProduct $product
     * @param string       $imageId
     * @param string[]     $attributes
     *
     * @return Image
     */
    protected function getImage(ModelProduct $product, string $imageId, array $attributes = []): Image
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}
