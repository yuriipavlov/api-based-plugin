<?php

namespace APIBasedPluginBlocks\APIData;

defined('ABSPATH') || exit;

use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Handlers\Blocks\BlockAbstract;
use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Repository\APIDataRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Block controller
 *
 * @package    API Based
 */
class BlockRenderer extends BlockAbstract
{
    /**
     * Block server side render callback
     * Used in register block type from metadata
     *
     * @param $attributes
     * @param $content
     * @param $block
     *
     * @return string
     */
    public static function blockServerSideCallback($attributes, $content, $block): string
    {
        $templateData = [];
        $APIData = APIDataRepository::getAPIData();
        $templateData['apiData']     = $APIData;

        return self::loadBlockView('layout', $templateData);
    }

    /**
     * Register rest api endpoints
     * Runs by Blocks Register Handler
     *
     * @return void
     */
    public static function blockRestApiEndpoints(): void
    {
        register_rest_route(Config::get('restApiNamespace'), '/api-data', [
            'methods'             => 'GET',
            'callback'            => [self::class, 'getDataCallback'],
            'permission_callback' => '__return_true',
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getDataCallback(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        $requestData = esc_sql(json_decode($request->get_body(), true));
        if (empty($requestData)) {
            status_header(404);
            nocache_headers();
            exit;
        }
        $params        = $requestData['params'];
        $requestedPage = $requestData['page'] ?? 1;
        //$nonce         = $requestData['nonce'];

        $APIData = APIDataRepository::getAPIData();

        return rest_ensure_response($APIData);
    }
}
