<?php

declare(strict_types=1);

namespace Web200\Seo\Service\HrefLang;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\CmsUrlRewrite\Model\CmsPageUrlPathGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\Store;
use Web200\Seo\Api\CmsPageUrlRetrieverInterface;

/**
 * Class CmsPageUrlRetriever
 *
 * @package   Web200\Seo\Service\HrefLang
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CmsPageUrlRetriever implements CmsPageUrlRetrieverInterface
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;
    /**
     * @var CmsPageUrlPathGenerator
     */
    protected $cmsPageUrlPathGenerator;
    /**
     * @var PageResource
     */
    protected $pageResource;

    /**
     * CmsPageUrlRetriever constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     * @param CmsPageUrlPathGenerator $cmsPageUrlPathGenerator
     * @param PageResource            $pageResource
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        CmsPageUrlPathGenerator $cmsPageUrlPathGenerator,
        PageResource $pageResource
    ) {
        $this->pageRepository = $pageRepository;
        $this->cmsPageUrlPathGenerator = $cmsPageUrlPathGenerator;
        $this->pageResource = $pageResource;
    }

    /**
     * Get url
     *
     * @param int $identifier The page ID
     * @param Store $store
     * @return string
     */
    public function getUrl($identifier, $store): string
    {
        try {
            $page = $this->pageRepository->getById($identifier);
            $pageId = $this->pageResource->checkIdentifier($page->getIdentifier(), $store->getId());
            $storePage = ($identifier === $pageId) ? $page : $this->pageRepository->getById($pageId);
            $path = $this->cmsPageUrlPathGenerator->getUrlPath($storePage);
            return $store->getBaseUrl() . $path;
        } catch (LocalizedException $e) {
            return '';
        }
    }
}
