<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Adapter;

use Magento\Cms\Model\Page as CmsPage;
use Magento\Framework\UrlInterface;
use Web200\Seo\Api\Data\AdapterInterface;
use Web200\Seo\Api\Data\PropertyInterface;
use Web200\Seo\Model\Property;

/**
 * Class Page
 *
 * @package   Web200\Seo\Model\Adapter
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Page implements AdapterInterface
{
    /**
     * Property interface
     *
     * @var PropertyInterface $property
     */
    protected $property;
    /**
     * Cms page
     *
     * @var CmsPage $page
     */
    protected $page;
    /**
     * Url interface
     *
     * @var UrlInterface $url
     */
    protected $url;

    /**
     * Page constructor.
     *
     * @param CmsPage           $page
     * @param UrlInterface      $url
     * @param PropertyInterface $property
     */
    public function __construct(
        CmsPage $page,
        UrlInterface $url,
        PropertyInterface $property
    ) {
        $this->property = $property;
        $this->page     = $page;
        $this->url      = $url;
    }

    /**
     * Get property
     *
     * @return PropertyInterface
     */
    public function getProperty(): PropertyInterface
    {
        if ($this->page->getId()) {
            $this->property->setTitle((string)$this->page->getTitle());
            $this->property->setDescription((string)$this->page->getMetaDescription());
            $this->property->setUrl((string)$this->url->getUrl($this->page->getIdentifier()));
            $this->property->addProperty('item', $this->page->getData(), Property::META_DATA_GROUP);
        }

        return $this->property;
    }
}
