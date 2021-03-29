<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin;

use Magento\Cms\Controller\Index\Index;
use Magento\Cms\Controller\Page\View;
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
     * Page config
     *
     * @var PageConfig $pageConfig
     */
    protected $pageConfig;
    /**
     * Url builder
     *
     * @var UrlInterface $urlBuilder
     */
    protected $urlBuilder;
    /**
     * Canonical config
     *
     * @var CanonicalConfig $canonicalConfig
     */
    protected $canonicalConfig;

    /**
     * AddCmsCanonical constructor.
     *
     * @param CanonicalConfig $canonicalConfig
     * @param PageConfig      $pageConfig
     * @param UrlInterface    $urlBuilder
     */
    public function __construct(
        CanonicalConfig $canonicalConfig,
        PageConfig $pageConfig,
        UrlInterface $urlBuilder
    ) {
        $this->pageConfig      = $pageConfig;
        $this->urlBuilder      = $urlBuilder;
        $this->canonicalConfig = $canonicalConfig;
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

        $this->pageConfig->addRemotePageAsset(
            $this->getCurrentUrl(),
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
