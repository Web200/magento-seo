<?php

declare(strict_types=1);

namespace Web200\Seo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Web200\Seo\Api\Data\SeoBlockInterface;
use Web200\Seo\Api\Data\PropertyInterface;
use Web200\Seo\Provider\SiteVerificationConfig;

/**
 * Class SiteVerification
 *
 * @package   Web200\Seo\Block
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class SiteVerification extends Template implements SeoBlockInterface
{
    /**
     * Google site verification
     *
     * @var string GOOLE_SITE_VERIFICATION
     */
    public const GOOLE_SITE_VERIFICATION = 'google-site-verification';
    /**
     * Ms validate 01
     *
     * @var string MSVALIDATE_01
     */
    public const MSVALIDATE_01 = 'msvalidate.01';
    /**
     * P domain verify
     *
     * @var string P_DOMAIN_VERIFY
     */
    public const P_DOMAIN_VERIFY = 'p:domain_verify';
    /**
     * Yandex verification
     *
     * @var string YANDEX_VERIFICATION
     */
    public const YANDEX_VERIFICATION = 'yandex-verification';
    /**
     * Site verification config
     *
     * @var SiteVerificationConfig $config
     */
    protected $config;
    /**
     * Property interface
     *
     * @var PropertyInterface $property
     */
    protected $property;

    /**
     * SiteVerification constructor.
     *
     * @param Context                $context
     * @param SiteVerificationConfig $config
     * @param PropertyInterface      $property
     * @param mixed[]                $data
     */
    public function __construct(
        Context $context,
        SiteVerificationConfig $config,
        PropertyInterface $property,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->config   = $config;
        $this->property = $property;
    }

    /**
     * Get meta data
     *
     * @return string
     */
    public function getMetaData(): string
    {
        $this->property->addProperty(self::GOOLE_SITE_VERIFICATION, $this->config->getGoogleSiteVerificationCode());
        $this->property->addProperty(self::MSVALIDATE_01, $this->config->getBingSiteVerificationCode());
        $this->property->addProperty(self::P_DOMAIN_VERIFY, $this->config->getPinterestSiteVerificationCode());
        $this->property->addProperty(self::YANDEX_VERIFICATION, $this->config->getYandexSiteVerificationCode());

        return $this->property->toHtml();
    }
}
