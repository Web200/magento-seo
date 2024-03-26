<?php

declare(strict_types=1);

namespace Web200\Seo\Service\HrefLang;

use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Store\Model\Store;
use Web200\Seo\Api\CategoryUrlRetrieverInterface;
use Web200\Seo\Api\CmsPageUrlRetrieverInterface;
use Web200\Seo\Api\ProductUrlRetrieverInterface;

/**
 * Class AlternativeUrlService
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class AlternativeUrlService
{
    /**
     * Cms page url retriver interface
     *
     * @var CmsPageUrlRetrieverInterface $cmsPageUrlRetriever
     */
    protected $cmsPageUrlRetriever;
    /**
     * Category url retriver interface
     *
     * @var CategoryUrlRetrieverInterface $categoryUrlRetriever
     */
    protected $categoryUrlRetriever;
    /**
     * Product url retriver interface
     *
     * @var ProductUrlRetrieverInterface $productUrlRetriever
     */
    protected $productUrlRetriever;
    /**
     * Http request
     *
     * @var HttpRequest $request
     */
    protected $request;

    /**
     * AlternativeUrlService constructor.
     *
     * @param CmsPageUrlRetrieverInterface  $cmsPageUrlRetriever
     * @param CategoryUrlRetrieverInterface $categoryUrlRetriever
     * @param ProductUrlRetrieverInterface  $productUrlRetriever
     * @param HttpRequest                   $request
     */
    public function __construct(
        CmsPageUrlRetrieverInterface $cmsPageUrlRetriever,
        CategoryUrlRetrieverInterface $categoryUrlRetriever,
        ProductUrlRetrieverInterface $productUrlRetriever,
        HttpRequest $request
    ) {
        $this->cmsPageUrlRetriever = $cmsPageUrlRetriever;
        $this->categoryUrlRetriever = $categoryUrlRetriever;
        $this->productUrlRetriever = $productUrlRetriever;
        $this->request = $request;
    }

    /**
     * Get alternative url
     *
     * @param Store $store
     *
     * @return string
     */
    public function getAlternativeUrl($store, $id, $action): string
    {
        $url = '';
        switch ($action) {
            case 'category':
                $url = $this->categoryUrlRetriever->getUrl($id, $store);
                break;
            case 'product':
                $url = $this->productUrlRetriever->getUrl($id, $store);
                break;
            case 'cms':
                $url = $this->cmsPageUrlRetriever->getUrl($id, $store);
                break;
            case 'cms_index_index':
                $url = $store->getBaseUrl();
                break;
        }

        return $url;
    }
}
