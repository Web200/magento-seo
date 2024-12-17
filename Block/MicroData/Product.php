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
use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;


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
     * reviewCollectionFactory
     */
    protected $reviewCollectionFactory;


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
     * @param ReviewCollectionFactory $reviewCollectionFactory
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
        ReviewCollectionFactory $reviewCollectionFactory,
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
        $this->reviewCollectionFactory = $reviewCollectionFactory;

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
            /** @var string[] $imageUrls */
            $imageUrls = [];
            $galleryImages = $product->getMediaGalleryImages();
            if ($galleryImages) {
                foreach ($galleryImages as $image) {
                    $imageUrls[] = $image->getUrl();
                }
            }

            /** @var Datetime $available */
            $available = new Datetime();
            $available->add(new DateInterval('P365D'));
            /** @var string[] $offer */
            $offer = [
                '@type' => 'Offer',
                'priceCurrency' => $this->storeManager->getStore()->getCurrentCurrencyCode(),
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
                'image' => $imageUrls,
                'description' => $product->getShortDescription(),
                'sku' => $product->getSku(),
                'gtin' => $product->getGtin(),
                'mpn' => $product->getMpn(),
                'offers' => [
                    $offer
                ]
            ];

            $manufacturerAttribute = $product->getResource()->getAttribute('manufacturer');
            if ($manufacturerAttribute) {
                $manufacturerValue = $manufacturerAttribute->getFrontend()->getValue($product);
                if (!empty($manufacturerValue) && $manufacturerValue !== 'No') {
                    $final['Brand'] = [
                        '@type' => 'Brand',
                        'name' => $manufacturerValue
                    ];
                }
            }

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

            $reviews = [];
            $reviewCollection = $this->reviewCollectionFactory->create()
                ->addEntityFilter('product', $product->getId())
                ->addStoreFilter($this->storeManager->getStore()->getId())
                ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
                ->setDateOrder();

                foreach ($reviewCollection as $review) {
                    $reviews[] = [
                        '@type' => 'Review',
                        'reviewRating' => [
                            '@type' => 'Rating',
                            'bestRating' => '5',
                            'worstRating' => '1',
                        ],
                        'author' => [
                            '@type' => 'Person',
                            'name' => $review->getNickname()
                        ]
                    ];
                }

                if (!empty($reviews)) {
                    $final['review'] = $reviews;
                }

                // Check if the product is configurable
                if ($product->getTypeId() === 'configurable') {
                    $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                    $prices = [];
                    foreach ($childProducts as $childProduct) {
                        $prices[] = $childProduct->getFinalPrice();
                    }
                    $final['offersss'] = [
                        '@type' => 'AggregateOffer',
                        'offerCount' => count($childProducts),
                        'lowPrice' => min($prices),
                        'highPrice' => max($prices),
                        'priceCurrency' => $this->storeManager->getStore()->getCurrentCurrencyCode() // Dynamically set the currency
                    ];
                }


            return $this->serialize->serialize($final);
        }

        return '';
    }
}
