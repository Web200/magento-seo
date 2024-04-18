<?php

declare(strict_types=1);

namespace Web200\Seo\Model;

use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Web200\Seo\Provider\SitemapConfig;

/**
 * Class Sitemap
 *
 * @package   Web200\Seo\Model
 * @author    Web200 <contact@web200.fr>
 * @copyright 2024 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Sitemap implements ItemProviderInterface
{
    /**
     * @param SitemapItemInterfaceFactory $itemFactory
     * @param SitemapConfig               $sitemapConfig
     */
    public function __construct(
        protected SitemapItemInterfaceFactory $itemFactory,
        protected SitemapConfig $sitemapConfig
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {
        if ($this->sitemapConfig->isCategoryActive()) {
            return [
                $this->itemFactory->create([
                    'url'             => $this->sitemapConfig->getCategoryUrlKey(),
                    'priority'        => 0.5,
                    'changeFrequency' => 'weekly',
                ])
            ];
        }

        return [];
    }
}
