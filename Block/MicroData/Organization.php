<?php

declare(strict_types=1);

namespace Web200\Seo\Block\MicroData;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Header\Logo as HtmlLogo;

/**
 * Class Organization
 *
 * @package   Web200\Seo\Block\MicroData
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Organization extends Template
{
    /**
     * Json
     *
     * @var Json $serialize
     */
    protected $serialize;
    /**
     * Store manager interface
     *
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * Organization constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Json                  $serialize
     * @param Context               $context
     * @param mixed[]               $data
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Json $serialize,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->serialize    = $serialize;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function getCacheLifetime()
    {
        return 86400;
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        return [
            $this->_storeManager->getStore()->getId(),
            'microdata_logo',
        ];
    }

    /**
     * Display
     *
     * @return bool
     * @throws LocalizedException
     */
    public function display(): bool
    {
        return (bool)$this->getLayout()->getBlock('logo');
    }

    /**
     * Render Json
     *
     * @return string
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function renderJson(): string
    {
        /** @var HtmlLogo $logoBlock */
        $logoBlock = $this->getLayout()->getBlock('logo');
        if ($logoBlock) {
            /** @var string $websiteUrl */
            $websiteUrl = $this->storeManager->getStore()->getBaseUrl();
            /** @var string[] $final */
            $final = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'url' => $websiteUrl,
                'logo' => $logoBlock->getLogoSrc()
            ];

            return $this->serialize->serialize($final);
        }

        return '';
    }
}
