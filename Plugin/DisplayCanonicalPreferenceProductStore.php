<?php

declare(strict_types=1);

namespace Web200\Seo\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product\View;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Asset\GroupedCollection;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Result\Page;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Web200\Seo\Provider\CanonicalConfig;
use Web200\Seo\Service\HrefLang\AlternativeUrlService;

/**
 * Class DisplayCanonicalPreferenceProductStore
 *
 * @package   Web200\Seo\Plugin
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class DisplayCanonicalPreferenceProductStore
{
    /**
     * Page assets
     *
     * @var GroupedCollection $pageAssets
     */
    protected $pageAssets;
    /**
     * Product repository
     *
     * @var ProductRepositoryInterface $productRepository
     */
    protected $productRepository;
    /**
     * Store manager
     *
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;
    /**
     * Alternative url service
     *
     * @var AlternativeUrlService $alternativeUrlService
     */
    protected $alternativeUrlService;
    /**
     * Canonical config
     *
     * @var CanonicalConfig $canonicalConfig
     */
    protected $canonicalConfig;
    /**
     * Asset repo
     *
     * @var Repository $assetRepo
     */
    protected $assetRepo;
    /**
     * Store repository
     *
     * @var StoreRepositoryInterface $storeRepository
     */
    protected $storeRepository;

    /**
     * @param StoreRepositoryInterface   $storeRepository
     * @param StoreManagerInterface      $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param AlternativeUrlService      $alternativeUrlService
     * @param CanonicalConfig            $canonicalConfig
     * @param Repository                 $assetRepo
     * @param GroupedCollection          $pageAssets
     */
    public function __construct(
        StoreRepositoryInterface $storeRepository,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        AlternativeUrlService $alternativeUrlService,
        CanonicalConfig $canonicalConfig,
        Repository $assetRepo,
        GroupedCollection $pageAssets
    ) {
        $this->pageAssets            = $pageAssets;
        $this->productRepository     = $productRepository;
        $this->storeManager          = $storeManager;
        $this->alternativeUrlService = $alternativeUrlService;
        $this->canonicalConfig       = $canonicalConfig;
        $this->assetRepo             = $assetRepo;
        $this->storeRepository       = $storeRepository;
    }

    /**
     * @param View            $subject
     * @param                 $result
     * @param Page            $resultPage
     * @param int             $productId
     * @param Action          $controller
     * @param DataObject|null $params
     */
    public function afterPrepareAndRender(
        View $subject,
        $result,
        Page $resultPage,
        $productId,
        $controller,
        $params = null
    ) {

        if (!$this->canonicalConfig->isStorePreferenceEnable()) {
            return $result;
        }

        $storeId = (int)$this->storeManager->getStore()->getId();

        try {
            $product       = $this->productRepository->getById(
                $productId,
                false,
                $storeId
            );
            $totalWebsites = count($product->getWebsiteStoreIds());
            if ($totalWebsites > 1 &&
                $this->canonicalConfig->getStorePreferenceStore() !== $storeId) {
                $name = $product->getUrlModel()->getUrl($product, ['_ignore_category' => true]);
                $this->pageAssets->remove($name);

                $store = $this->storeRepository->getById($this->canonicalConfig->getStorePreferenceStore());
                $url   = $this->alternativeUrlService->getAlternativeUrl($store);

                $remoteAsset = $this->assetRepo->createRemoteAsset($url, 'canonical');
                $this->pageAssets->add($name, $remoteAsset, ['attributes' => ['rel' => 'canonical']]);
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return $result;
    }
}
