<?php

declare(strict_types=1);

namespace Web200\Seo\Model;

use Exception;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;

/**
 * Class BlockParser
 *
 * @package   Web200\Seo\Model
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class BlockParser
{
    /**
     * Block repository interface
     *
     * @var BlockRepositoryInterface $blockRepository
     */
    protected $blockRepository;

    /**
     * BlockParser constructor.
     *
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository
    ) {
        $this->blockRepository = $blockRepository;
    }

    /**
     * Get blockContent by id
     *
     * @param int $blockId
     *
     * @return string
     */
    public function getBlockContentById(int $blockId): string
    {
        try {
            /** @var BlockInterface $cmsBlock */
            $cmsBlock = $this->blockRepository->getById($blockId);

            return html_entity_decode($cmsBlock->getData('content')) ?? '';
        } catch (Exception $e) {
            return '';
        }
    }
}
