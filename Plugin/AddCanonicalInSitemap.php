<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Escaper;
use Magento\Sitemap\Model\ItemProvider\Product;
use Magento\Sitemap\Model\SitemapItem;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Web200\Seo\Provider\CanonicalConfig;

/**
 * Class AddCanonicalInSitemap
 *
 * @package   Web200\Seo\Plugin
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class AddCanonicalInSitemap
{
    /**
     * Url constructor.
     *
     * @param CanonicalConfig             $canonicalConfig
     * @param ResourceConnection          $resource
     * @param SitemapItemInterfaceFactory $itemFactory
     * @param Escaper                     $escaper
     */
    public function __construct(
        protected CanonicalConfig $canonicalConfig,
        protected ResourceConnection $resource,
        protected SitemapItemInterfaceFactory $itemFactory,
        protected Escaper $escaper
    ) {
    }

    /**
     * After get items
     *
     * @param Product $subject
     * @param         $items
     * @param int     $storeId
     *
     * @return string[]
     */
    public function afterGetItems(Product $subject, $items, $storeId)
    {
        if (!$this->canonicalConfig->isSimpleProductSitemap($storeId)) {
            return $items;
        }

        $requestPaths = $this->getRequestPathData((int)$storeId);
        foreach ($requestPaths as $requestPath) {
            if (isset($items[$requestPath['entity_id']])) {
                /** @var SitemapItem $sitemapItem */
                $sitemapItem = $items[$requestPath['entity_id']];
                $url = $this->escaper->escapeUrl($requestPath['request_path']);
                $sitemapItem = $this->itemFactory->create([
                    'url'             => $url,
                    'updatedAt'       => $sitemapItem->getUpdatedAt(),
                    'images'          => $sitemapItem->getImages(),
                    'priority'        => $sitemapItem->getPriority(),
                    'changeFrequency' => $sitemapItem->getChangeFrequency(),
                ]);
                $items[$requestPath['entity_id']] = $sitemapItem;
            }
        }

        return $items;
    }

    /**
     * Get request path data
     *
     * @param int $storeId
     *
     * @return string[]
     */
    protected function getRequestPathData(int $storeId): array
    {
        $connection = $this->resource->getConnection();
        $select     = $connection->select()->from(
            ['url_rewrite' => $connection->getTableName('url_rewrite')],
            ['request_path', 'entity_id']
        )->where('entity_type = ?', 'product')
            ->where('store_id = ?', $storeId)
            ->where('redirect_type = 0')
            ->where('metadata IS NULL');

        return $connection->fetchAll($select);
    }
}
