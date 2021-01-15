<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Config\Source\TwitterCard;

use Magento\Framework\Data\OptionSourceInterface;
use Web200\Seo\Model\Property\TwitterCard;

/**
 * Class Type
 *
 * @package   Web200\Seo\Model\Config\Source\TwitterCard
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Type implements OptionSourceInterface
{
    /**
     * Card type summary
     *
     * @var string CARD_TYPE_SUMMARY
     */
    protected const CARD_TYPE_SUMMARY = 'summary';
    /**
     * Card type summary large image
     *
     * @var string CARD_TYPE_SUMMARY_LARGE_IMAGE
     */
    protected const CARD_TYPE_SUMMARY_LARGE_IMAGE = 'summary_large_image';
    /**
     * Card type app
     *
     * @var string CARD_TYPE_APP
     */
    protected const CARD_TYPE_APP = 'app';
    /**
     * Card type player
     *
     * @var string CARD_TYPE_PLAYER
     */
    protected const CARD_TYPE_PLAYER = 'player';
    /**
     * Options array
     *
     * @var string[] $options
     */
    protected $options;

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [
                ['value' => self::CARD_TYPE_SUMMARY, 'label' => __('Summary')],
                ['value' => self::CARD_TYPE_SUMMARY_LARGE_IMAGE, 'label' => __('Summary with large Image')],
                ['value' => self::CARD_TYPE_APP, 'label' => __('App')],
                ['value' => self::CARD_TYPE_PLAYER, 'label' => __('Player')],
            ];
        }

        return $this->options;
    }
}
