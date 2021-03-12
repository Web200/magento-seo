<?php

declare(strict_types=1);

namespace Web200\Seo\Block\MicroData;

use DateInterval;
use Datetime;
use Magento\Catalog\Block\Product\ReviewRendererInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product as ModelProduct;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Review\Model\Review\Summary;
use Magento\Review\Model\Review\SummaryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Web200\Seo\Provider\MicrodataConfig;

/**
 * Class Product
 *
 * @package   Web200\Seo\Block\MicroData
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Product extends Template
{
    /**
     * Json
     *
     * @var Json $serialize
     */
    protected $serialize;
    /**
     * Store manager
     *
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;
    /**
     * Registry
     *
     * @var Registry $registry
     */
    protected $registry;
    /**
     * Image
     *
     * @var Image $image
     */
    protected $image;
    /**
     * Review renderer interface
     *
     * @var ReviewRendererInterface $reviewRenderer
     */
    protected $reviewRenderer;
    /**
     * Summary factory
     *
     * @var SummaryFactory $reviewSummaryFactory
     */
    protected $reviewSummaryFactory;
    /**
     * Config
     *
     * @var MicrodataConfig $config
     */
    protected $config;

    /**
     * Product constructor.
     *
     * @param MicrodataConfig         $config
     * @param StoreManagerInterface   $storeManager
     * @param Json                    $serialize
     * @param Registry                $registry
     * @param Image                   $image
     * @param ReviewRendererInterface $reviewRenderer
     * @param SummaryFactory          $reviewSummaryFactory
     * @param Context                 $context
     * @param mixed[]                 $data
     */
    public function __construct(
        MicrodataConfig $config,
        StoreManagerInterface $storeManager,
        Json $serialize,
        Registry $registry,
        Image $image,
        ReviewRendererInterface $reviewRenderer,
        SummaryFactory $reviewSummaryFactory,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->serialize            = $serialize;
        $this->storeManager         = $storeManager;
        $this->registry             = $registry;
        $this->image                = $image;
        $this->reviewRenderer       = $reviewRenderer;
        $this->reviewSummaryFactory = $reviewSummaryFactory;
        $this->config = $config;
    }

    /**
     * Display
     *
     * @return bool
     */
    public function display(): bool
    {
        return (bool)$this->registry->registry('product');
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
        /** @var ModelProduct $product */
        $product = $this->registry->registry('product');
        if ($product) {
            /** @var string $websiteUrl */
            $websiteUrl = $this->storeManager->getStore()->getBaseUrl();
            /** @var string $imageUrl */
            $imageUrl = $this->image->init($product, 'product_base_image')
                ->setImageFile($product->getFile())->getUrl();

            /** @var Datetime $available */
            $available = new Datetime();
            $available->add(new DateInterval('P365D'));
            /** @var string[] $offer */
            $offer = [
                '@type' => 'Offer',
                'priceCurrency' => 'EUR',
                'url' => $product->getProductUrl(),
                'price' => round($product->getFinalPrice(), 2),
                'priceValidUntil' => $available->format('Y-m-d')
            ];
            if ($product->isAvailable()) {
                $offer['availability'] = 'https://schema.org/InStock';
            } else {
                $offer['availability'] = 'https://schema.org/OutOfStock';
            }

            /** @var string[] $final */
            $final = [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->getName(),
                'image' => $imageUrl,
                'description' => $product->getShortDescription(),
                'sku' => $product->getSku(),
                'offers' => [
                    $offer
                ]
            ];

            /** @var string $brand */
            $brand = $this->config->getBrand();
            if ($brand !== '') {
                /** @var Attribute $brandAttribute */
                $brandAttribute = $product->getResource()->getAttribute($brand);
                if ($brandAttribute) {
                    $brandValue = $brandAttribute->getFrontend()->getValue($product);
                    if ($brandValue !== false) {
                        $final['brand'] = $brandValue;
                    }
                }
            }

            /** @var Summary $reviewSummary */
            $reviewSummary = $this->reviewSummaryFactory->create();
            $reviewSummary->setData('store_id', $this->storeManager->getStore()->getId());
            /** @var Summary $summaryModel */
            $summaryModel = $reviewSummary->load($product->getId());
            /** @var int $reviewCount */
            $reviewCount = (int)$summaryModel->getReviewsCount();
            if (!$reviewCount) {
                $reviewCount = 0;
            }
            /** @var int $ratingSummary */
            $ratingSummary = ($summaryModel->getRatingSummary()) ? (int)$summaryModel->getRatingSummary() : 20;
            if ($reviewCount) {
                $final['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingValue' => $ratingSummary / 20,
                    'reviewCount' => $reviewCount,
                ];
            }

            return $this->serialize->serialize($final);
        }

        return '';
    }
}
