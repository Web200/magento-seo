<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin;

use Magento\Cms\Controller\Index\Index;
use Magento\Cms\Controller\Page\View;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Web200\Seo\Provider\CanonicalConfig;

/**
 * Class AddCmsCanonical
 *
 * @package   Web200\Seo\Plugin
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class AddCmsCanonical
{
    /**
     * AddCmsCanonical constructor.
     *
     * @param CanonicalConfig $canonicalConfig
     * @param PageConfig      $pageConfig
     * @param UrlInterface    $urlBuilder
     * @param Escaper         $escaper
     */
    public function __construct(
        protected CanonicalConfig $canonicalConfig,
        protected PageConfig $pageConfig,
        protected UrlInterface $urlBuilder,
        protected Escaper $escaper
    ) {
    }

    /**
     * After execute
     *
     * @param View|Index $subject
     * @param            $result
     *
     * @return mixed
     */
    public function afterExecute($subject, $result)
    {
        if (!$this->canonicalConfig->isCmsActive()) {
            return $result;
        }

        $currentUrl = $this->getCurrentUrl();
        if ($subject instanceof Index) {
            $currentUrl = rtrim($currentUrl, '/');
        }

        $this->pageConfig->addRemotePageAsset(
            $this->escaper->escapeUrl($currentUrl),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        return $result;
    }

    /**
     * Retrieve page URL by defined parameters
     *
     * @return string
     */
    protected function getCurrentUrl(): string
    {
        $urlParams                 = [];
        $urlParams['_current']     = false;
        $urlParams['_escape']      = true;
        $urlParams['_use_rewrite'] = true;

        return $this->urlBuilder->getUrl('*/*/*', $urlParams);
    }
}
