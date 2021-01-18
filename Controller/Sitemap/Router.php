<?php

declare(strict_types=1);

namespace Web200\Seo\Controller\Sitemap;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Web200\Seo\Provider\SitemapConfig;

/**
 * Class Router
 *
 * @package   Web200\Seo\Controller\Sitemap
 * @author    Web200 <contact@web200.fr>
 * @copyright 2021 Web200
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.web200.fr/
 */
class Router implements RouterInterface
{
    /**
     * Action factory
     *
     * @var ActionFactory $actionFactory
     */
    protected $actionFactory;
    /**
     * Response
     *
     * @var ResponseInterface $response
     */
    protected $response;
    /**
     * Dispatched
     *
     * @var bool $dispatched
     */
    protected $dispatched;
    /**
     * Sitemap config
     *
     * @var SitemapConfig $config
     */
    protected $config;

    /**
     * Router constructor.
     *
     * @param ActionFactory     $actionFactory
     * @param ResponseInterface $response
     * @param SitemapConfig     $config
     */
    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        SitemapConfig $config
    ) {
        $this->actionFactory = $actionFactory;
        $this->response      = $response;
        $this->config        = $config;
    }

    /**
     * Match
     *
     * @param RequestInterface $request
     *
     * @return ActionInterface|void
     */
    public function match(RequestInterface $request)
    {
        if (!$this->dispatched) {
            /** @var string $identifier */
            $identifier = trim($request->getPathInfo(), '/');

            /** @var string $categoryUrlKey */
            $categoryUrlKey = $this->config->getCategoryUrlKey();
            if ($this->config->isCategoryActive() && $categoryUrlKey !== '') {
                if ($identifier === $categoryUrlKey) {
                    $request
                        ->setModuleName('seo')
                        ->setControllerName('sitemap')
                        ->setActionName('category');
                    $this->dispatched = true;
                }
            }

            if ($this->dispatched) {
                return $this->actionFactory->create(Forward::class, ['request' => $request]);
            }
        }
    }
}
