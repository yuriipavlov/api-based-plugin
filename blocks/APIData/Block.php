<?php

namespace APIBasedPluginBlocks\APIData;

defined('ABSPATH') || exit;

use APIBasedPlugin\Handlers\Blocks\BlockAbstract;
use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Repository\APIDataRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Block controller
 *
 * @package    API Based
 */
class Block extends BlockAbstract
{
    /**
     * Block assets for editor and frontend
     *
     * @var array
     */
    protected array $blockAssets
        = [
            'editor_script' => [
                'file' => 'index.js',
                'dependencies' => ['wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor'],
            ],
            'view_script' => [
                'file' => 'view.js',
                'dependencies' => [],
            ],
            'editor_style' => [],
            'style' => [
                'file' => 'style.css',
                'dependencies' => [],
            ],
            'view_style' => [],
        ];

    /**
     * Register block additional arguments including server side render callback
     *
     * @return void
     */
    public function registerBlockArgs(): void
    {
        $this->blockArgs['render_callback'] = [$this, 'blockServerSideCallback'];
    }

    /**
     * Block server side render callback
     * Used in register block type from metadata
     *
     * @param array  $attributes
     * @param string $content
     * @param object $block
     *
     * @return string
     *
     * @throws NotFoundException
     * @throws Throwable
     */
    public function blockServerSideCallback(array $attributes, string $content, object $block): string
    {
        $templateData['text'] = esc_html__('API Data before loading template text', 'api-based-plugin');

        return $this->loadBlockView('loading', $templateData);
    }


    /**
     * Register rest api endpoints
     * Runs by abstract constructor
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function blockRestApiEndpoints(): void
    {
        register_rest_route(Config::get('restApiNamespace'), '/api-data', [
            'methods' => 'GET',
            'callback' => [$this, 'getDataCallback'],
            'permission_callback' => [$this, 'getNoncePermissionCheck'],
        ]);
    }

    /**
     * REST API endpoint callback
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response|WP_HTTP_Response
     *
     * @throws NotFoundException
     * @throws Throwable
     */
    public function getDataCallback(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        $params = $request->get_params();

        $templateData = [];

        $APIData = APIDataRepository::getAPIData();

        $templateData['apiData'] = $APIData->data;

/*
        if (!empty($params['columns'])) {
            $columnsData             = urldecode($params['columns']);
            $templateData['columns'] = json_decode(base64_decode($columnsData), true);
        }

        asort($templateData['columns']);*/

        $response = [
            'code' => 'success',
            'data' => $this->loadBlockView('layout', $templateData),
        ];

        return new WP_REST_Response($response, 200);
    }

    /**
     * Check nonce
     *
     * @param $request
     *
     * @return bool|int
     */
    public function getNoncePermissionCheck($request): bool
    {
        return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest');
    }
}
