<?php

declare(strict_types=1);

namespace Web200\Seo\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\Group;
use Magento\Store\Model\Store;
use Magento\Store\Model\Website;
use Web200\Seo\Service\HrefLang\AlternativeUrlService;

/**
 * Class HrefLang
 *
 * @package   Web200\Seo\Block
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class HrefLang extends Template
{
    /**
     * Alternative url service
     *
     * @var AlternativeUrlService $alternativeUrlService
     */
    protected $alternativeUrlService;

    /**
     * HrefLang constructor.
     *
     * @param Context               $context
     * @param AlternativeUrlService $alternativeUrlService
     * @param mixed[]               $data
     */
    public function __construct(
        Context $context,
        AlternativeUrlService $alternativeUrlService,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->alternativeUrlService = $alternativeUrlService;
    }

    /**
     * Get alternates
     *
     * @return string[]
     * @throws LocalizedException
     */
    public function getAlternates(): array
    {
        /** @var string[] $data */
        $data = [];
        /** @var Store $store */
        foreach ($this->getStores() as $store) {
            /** @var string $url */
            $url = $this->getStoreUrl($store);
            if ($url) {
                $data[$this->getLocaleCode($store)] = $url;
            }
        }

        return $data;
    }

    /**
     * Get store Url
     *
     * @param Store $store
     *
     * @return string
     */
    protected function getStoreUrl(Store $store): string
    {
        #its just for testing purpose
        return $this->alternativeUrlService->getAlternativeUrl($store, 1, 'product');
    }

    /**
     * Is current store
     *
     * @param Store $store
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    private function isCurrentStore(Store $store): bool
    {
        return $store->getId() == $this->_storeManager->getStore()->getId();
    }

    /**
     * Get locale code
     *
     * @param Store $store
     *
     * @return string
     */
    private function getLocaleCode(Store $store): string
    {
        /** @var string $localeCode */
        $localeCode = $this->_scopeConfig->getValue('seo/hreflang/locale_code', 'stores', $store->getId())
            ?: $this->_scopeConfig->getValue('general/locale/code', 'stores', $store->getId());

        return str_replace('_', '-', strtolower($localeCode));
    }

    /**
     * Get stores
     *
     * @return string[]
     * @throws LocalizedException
     */
    protected function getStores(): array
    {
        if ($this->_scopeConfig->isSetFlag('seo/hreflang/same_website_only')) {
            return $this->getSameWebsiteStores();
        }

        return $this->_storeManager->getStores();
    }

    /**
     * Get same website stores
     *
     * @return string[]
     * @throws LocalizedException
     */
    protected function getSameWebsiteStores(): array
    {
        /** @var string[] $stores */
        $stores = [];
        /** @var Website $website */
        $website = $this->_storeManager->getWebsite();
        /** @var Store[] $group */
        foreach ($website->getGroups() as $group) {
            /** @var Group $group */
            foreach ($group->getStores() as $store) {
                $stores[] = $store;
            }
        }

        return $stores;
    }
}
