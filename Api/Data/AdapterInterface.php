<?php

declare(strict_types=1);

namespace Web200\Seo\Api\Data;

/**
 * Interface AdapterInterface
 *
 * @package   Web200\Seo\Api\Data
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
interface AdapterInterface
{
    /**
     * Get property
     *
     * @return PropertyInterface
     */
    public function getProperty(): PropertyInterface;
}
