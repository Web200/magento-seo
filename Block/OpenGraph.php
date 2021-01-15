<?php

declare(strict_types=1);

namespace Web200\Seo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Web200\Seo\Api\Data\SeoBlockInterface;
use Web200\Seo\Api\Data\AdapterInterface;
use Web200\Seo\Model\Property;

/**
 * Class OpenGraph
 *
 * @package   Web200\Seo\Block
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class OpenGraph extends Template implements SeoBlockInterface
{
    /**
     * Adapter interface
     *
     * @var AdapterInterface $adapter
     */
    private $adapter;

    /**
     * OpenGraph constructor.
     *
     * @param Context          $context
     * @param AdapterInterface $adapter
     * @param array            $data
     */
    public function __construct(
        Context $context,
        AdapterInterface $adapter,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->adapter = $adapter;
    }

    /**
     * Get metadata
     *
     * @return string
     */
    public function getMetaData(): string
    {
        /** @var Property $property */
        $property = $this->adapter->getProperty();
        /** @var string $openGraph */
        $openGraph = $property
            ->setPrefix('og:')
            ->setMetaAttributeName('property')
            ->toHtml();

        /** @var string $productInformation */
        $productInformation = $property
            ->setMetaAttributeName('property')
            ->toHtml('product');

        return sprintf(
            '%s%s',
            $openGraph,
            $productInformation
        );
    }
}
