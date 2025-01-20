<?php

declare(strict_types=1);

namespace Web200\Seo\Controller\Sitemap;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Category
 *
 * @package   Web200\Seo\Controller\Sitemap
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Category extends Action
{
    /**
     * Page factory
     *
     * @var PageFactory $pageFactory
     */
    protected $pageFactory;
    /**
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Url Interface
     *
     * @var UrlInterface $urlBuilder
     */
    protected $urlBuilder;

    /**
     * Category constructor.
     *
     * @param Context      $context
     * @param PageFactory  $pageFactory
     * @param PageConfig   $pageConfig
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        PageConfig $pageConfig,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);

        $this->pageFactory = $pageFactory;
        $this->pageConfig = $pageConfig;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var Page $pageFactory */
        $pageFactory = $this->pageFactory->create();
        $pageFactory->getConfig()->getTitle()->prepend(__('Sitemap'));

        if (!$this->pageConfig->getAssetCollection()->getGroupByContentType('canonical')) {
            $this->pageConfig->addRemotePageAsset(
                $this->urlBuilder->getCurrentUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        return $pageFactory;
    }
}
