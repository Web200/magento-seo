<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin\MetaRobots;

use Magento\Cms\Controller\Index\Index;
use Magento\Cms\Helper\Page as HelperPage;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Store\Model\ScopeInterface;
use Web200\Seo\Provider\MetaRobotsConfig;

/**
 * Class CmsIndex
 *
 * @package   Web200\Seo\Plugin\MetaRobots\CmsIndex
 * @author    Web200 <contact@web200.fr>
 * @copyright 2023 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class CmsIndex
{
    /**
     * Cms Constructor
     *
     * @param PageConfig           $pageConfig
     * @param PageRepository       $pageRepository
     * @param RequestInterface     $request
     * @param MetaRobotsConfig     $metaRobotsConfig
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected PageConfig $pageConfig,
        protected PageRepository $pageRepository,
        protected RequestInterface $request,
        protected MetaRobotsConfig $metaRobotsConfig,
        protected ScopeConfigInterface $scopeConfig
    ) {
    }
    /**
     * @param Index $subject
     * @param bool  $result
     *
     * @return bool
     */
    public function afterExecute($subject, $result)
    {
        $pageId = $this->scopeConfig->getValue(HelperPage::XML_PATH_HOME_PAGE, ScopeInterface::SCOPE_STORE);
        if ($pageId > 0) {
            try {
                $cmsPage = $this->pageRepository->getById($pageId);

                $metaRobots = (string)$cmsPage->getMetaRobots() === '' ? $this->metaRobotsConfig->getDefaultRobots() : $cmsPage->getMetaRobots();

                $this->pageConfig->setMetadata('robots', $metaRobots);
            } catch (NoSuchEntityException $noSuchEntityException) {
            }
        }

        return $result;
    }
}
