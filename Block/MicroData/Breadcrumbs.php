<?php

declare(strict_types=1);

namespace Web200\Seo\Block\MicroData;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Breadcrumbs as HtmlBreadcrumbs;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

/**
 * Class Breadcrumbs
 *
 * @package   Web200\Seo\Block\MicroData
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Breadcrumbs extends Template
{
    /**
     * Json
     *
     * @var Json $json
     */
    protected $json;
    /**
     * Items
     *
     * @var $items
     */
    protected $items = null;

    /**
     * Breadcrumbs constructor.
     *
     * @param Json    $json
     * @param Context $context
     * @param mixed[] $data
     */
    public function __construct(
        Json $json,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->json = $json;
    }

    /**
     * Get Items
     *
     * @return array
     */
    public function getItems(): array
    {
        if ($this->items === null) {
            $this->items = [];

            /** @var HtmlBreadcrumbs $breadcrumbBlock */
            $breadcrumbBlock = $this->_layout->getBlock('breadcrumbs');
            if ($breadcrumbBlock && isset($breadcrumbBlock->getCacheKeyInfo()['crumbs'])) {
                /** @var string[] $breadcrumbs */
                $breadcrumbs = $this->json->unserialize(base64_decode($breadcrumbBlock->getCacheKeyInfo()['crumbs']));
                if (!empty($breadcrumbs)) {
                    /** @var string $code */
                    /** @var string[] $values */
                    foreach ($breadcrumbs as $code => $values) {
                        $item          = [
                            'link' => $values['link'],
                            'label' => $values['label'],
                            'title' => $values['label'],
                        ];
                        $this->items[] = $item;
                    }
                }
            }
        }

        return $this->items;
    }

    /**
     * Display
     *
     * @return bool
     */
    public function display(): bool
    {
        return !empty($this->getItems());
    }

    /**
     * Json Render
     *
     * @return string
     */
    public function renderJson(): string
    {
        $final = [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];

        $position = 1;
        $crumbs   = $this->getItems();
        foreach ($crumbs as $crumb) {
            if (!isset($crumb['link'])) {
                continue;
            }
            $final['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => $crumb['link'],
                    'name' => $crumb['label']
                ]
            ];
        }

        return $this->json->serialize($final);
    }
}
