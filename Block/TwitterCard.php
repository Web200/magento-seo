<?php

declare(strict_types=1);

namespace Web200\Seo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Web200\Seo\Api\Data\SeoBlockInterface;
use Web200\Seo\Model\Property;
use Web200\Seo\Provider\TwitterCardConfig;
use Web200\Seo\Api\Data\AdapterInterface;

/**
 * Class TwitterCard
 *
 * @package   Web200\Seo\Block
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class TwitterCard extends Template implements SeoBlockInterface
{
    /**
     * Twitter card config
     *
     * @var TwitterCardConfig $config
     */
    protected $config;
    /**
     * Adapter interface
     *
     * @var AdapterInterface $adapter
     */
    protected $adapter;

    /**
     * TwitterCard constructor.
     *
     * @param TwitterCardConfig $config
     * @param Context           $context
     * @param AdapterInterface  $adapter
     * @param mixed[]           $data
     */
    public function __construct(
        TwitterCardConfig $config,
        Context $context,
        AdapterInterface $adapter,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->adapter = $adapter;
        $this->config  = $config;
    }

    /**
     * Get meta data
     *
     * @return string
     */
    public function getMetaData(): string
    {
        /** @var Property $property */
        $property = $this->adapter->getProperty();

        $property->setPrefix('twitter:');
        if ($this->config->getDefaultTwitterCardType() !== '') {
            $property->addProperty('card', $this->config->getDefaultTwitterCardType());
        }
        if ($this->config->getDefaultTwitterCardSite() !== '') {
            $property->addProperty('site', $this->config->getDefaultTwitterCardSite());
        }
        if ($this->config->getDefaultTwitterCardCreator() !== '') {
            $property->addProperty('creator', $this->config->getDefaultTwitterCardCreator());
        }

        return $property->toHtml();
    }
}
