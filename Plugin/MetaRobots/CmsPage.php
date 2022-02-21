<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin\MetaRobots;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Controller\Page\View;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Web200\Seo\Provider\MetaRobotsConfig;

/**
 * Class Product
 *
 * @package   Web200\Seo\Plugin\MetaRobots
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CmsPage
{
    protected $pageRepository;
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Registry
     *
     * @var Registry $registry
     */
    protected $registry;
    /**
     * Meta robots config
     *
     * @var MetaRobotsConfig $metaRobotsConfig
     */
    protected $metaRobotsConfig;

    /**
     * Product constructor.
     *
     * @param PageConfig       $pageConfig
     * @param MetaRobotsConfig $metaRobotsConfig
     * @param Registry         $registry
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Cms\Helper\Page $pageHelper,
        PageConfig $pageConfig,
        MetaRobotsConfig $metaRobotsConfig,
        Registry $registry
    ) {
        $this->pageRepository = $pageRepository;
        $this->request = $request;
        $this->pageConfig       = $pageConfig;
        $this->pageHelper         = $pageHelper;
        $this->registry         = $registry;
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
        $pageId = $this->request->getParam('page_id', $this->request->getParam('id', false));
        $pageResult = $this->pageRepository->getById($pageId);

        if ($page) {
            $metaRobots = (string)$pageResult->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $pageResult->getMetaRobots();
            $this->pageConfig->setMetadata('robots', $metaRobots);
        }

        return $page;
    }
}
