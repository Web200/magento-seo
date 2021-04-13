<?php

declare(strict_types=1);

namespace Web200\Seo\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class MetaRobots
 *
 * @package   Web200\Seo\Model\Config\Source
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class MetaRobots extends AbstractSource implements OptionSourceInterface
{
    /**
     * Values
     *
     * @var string[] VALUES
     */
    public const VALUES = [
        'INDEX, FOLLOW',
        'NOINDEX, FOLLOW',
        'INDEX, NOFOLLOW',
        'NOINDEX, NOFOLLOW'
    ];

    /**
     * @inheritdoc
     */
    public function getAllOptions(): array
    {
        $array   = [];
        $array[] = ['value' => '', 'label' => ' '];
        foreach (self::VALUES as $value) {
            $array[] = [
                'value' => $value,
                'label' => $value
            ];
        }

        return $array;
    }
}
