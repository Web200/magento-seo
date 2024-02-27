<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin\MetaRobots;

use Magento\Catalog\Controller\Category\View;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Web200\Seo\Provider\MetaRobotsConfig;

/**
 * Class Category
 *
 * @package   Web200\Seo\Plugin\MetaRobots
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Category
{
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Meta robots config
     *
     * @var MetaRobotsConfig $metaRobotsConfig
     */
    protected $metaRobotsConfig;
    /**
     * Request
     *
     * @var RequestInterface $request
     */
    protected $request;

    /**
     * Category constructor.
     *
     * @param RequestInterface $request
     * @param PageConfig       $pageConfig
     * @param MetaRobotsConfig $metaRobotsConfig
     */
    public function __construct(
        RequestInterface $request,
        PageConfig $pageConfig,
        MetaRobotsConfig $metaRobotsConfig
    ) {
        $this->pageConfig       = $pageConfig;
        $this->metaRobotsConfig = $metaRobotsConfig;
        $this->request          = $request;
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
        if (!$this->metaRobotsConfig->isCategoryNoIndexActive()) {
            return $page;
        }

        $params = array_keys($this->request->getParams());
        if ($params === ['id']) {
            return $page;
        }

        if (!empty(array_diff($params, ['id', 'p']))) {
            $this->pageConfig->setMetadata('robots', 'NOINDEX, FOLLOW');
        }

        return $page;
    }
}
