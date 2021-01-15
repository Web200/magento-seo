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

/**
 * Class SitelinksSearchbox
 *
 * @package   Web200\Seo\Block\MicroData
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class SitelinksSearchbox extends Template
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
     * SitelinksSearchbox constructor.
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
        $this->serialize    = $serialize;
        $this->storeManager = $storeManager;

        parent::__construct($context, $data);
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
            'microdata_sitelink_searchbox',
        ];
    }

    /**
     * Display
     *
     * @return bool
     */
    public function display(): bool
    {
        return true;
    }
    /**
     * Render Json
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function renderJson(): string
    {
        /** @var string $websiteUrl */
        $websiteUrl = $this->storeManager->getStore()->getBaseUrl();
        /** @var string[] $final */
        $final = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => $websiteUrl,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $websiteUrl . 'catalogsearch/result/?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];

        return $this->serialize->serialize($final);
    }
}
