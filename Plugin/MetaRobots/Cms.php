<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin\MetaRobots;

use Magento\Cms\Controller\Page\View;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Web200\Seo\Provider\MetaRobotsConfig;
use Magento\Cms\Model\PageRepository;

/**
 * Class Cms
 *
 * @package   Web200\Seo\Plugin\MetaRobots
 * @author    Web200 <contact@web200.fr>
 * @copyright 2023 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Cms
{
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Page Repository
     *
     * @var PageRepository $pageRepository
     */
    protected $pageRepository;
    /**
     * Request Interface
     *
     * @var RequestInterface $request
     */
    protected $request;
    /**
     * Meta robots config
     *
     * @var MetaRobotsConfig $metaRobotsConfig
     */
    protected $metaRobotsConfig;

    /**
     * Cms Constructor
     *
     * @param PageConfig       $pageConfig
     * @param PageRepository   $pageRepository
     * @param RequestInterface $request
     * @param MetaRobotsConfig $metaRobotsConfig
     */
    public function __construct(
        PageConfig $pageConfig,
        PageRepository $pageRepository,
        RequestInterface $request,
        MetaRobotsConfig $metaRobotsConfig
    ) {
        $this->pageConfig       = $pageConfig;
        $this->pageRepository = $pageRepository;
        $this->request        = $request;
        $this->metaRobotsConfig = $metaRobotsConfig;
    }

    /**
     * Execute
     *
     * @param View $subject
     * @param Page $page
     *
     * @return mixed
     */
    public function afterExecute(View $subject, $page)
    {
        $pageId = $this->getPageId();
        if ($pageId > 0) {
            try {
                $cmsPage = $this->pageRepository->getById($this->getPageId());

                $metaRobots = (string)$cmsPage->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $cmsPage->getMetaRobots();

                $this->pageConfig->setMetadata('robots', $metaRobots);
            } catch (NoSuchEntityException $noSuchEntityException) {
            }
        }

        return $page;
    }

    /**
     * @return int|null
     */
    protected function getPageId(): ?int
    {
        $id = $this->request->getParam('page_id') ?? $this->request->getParam('id');

        return $id ? (int)$id : null;
    }
}
