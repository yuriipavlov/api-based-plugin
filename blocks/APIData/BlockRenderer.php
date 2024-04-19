<?php

namespace APIBasedPluginBlocks\APIData;

defined('ABSPATH') || exit;

use APIBasedPlugin\Handlers\Blocks\BlockAbstract;
use APIBasedPlugin\Helper\Config;
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
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function blockServerSideCallback($attributes, $content, $block): string
    {
        $templateData = [];

        $APIData = APIDataRepository::getAPIData();

        $templateData['apiData'] = $APIData;
        $templateData['columns'] = !empty($attributes['columns']) ? $attributes['columns'] : [];

        asort($templateData['columns']);

        return self::loadBlockView('layout', $templateData);
    }

    /**
     * Register rest api endpoints
     * Runs by Blocks Register Handler
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function blockRestApiEndpoints(): void
    {
        register_rest_route(Config::get('restApiNamespace'), '/api-data', [
            'methods'             => 'GET',
            'callback'            => [self::class, 'getDataCallback'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route(Config::get('restApiNamespace'), '/get-table-headers', [
            'methods'             => 'GET',
            'callback'            => [self::class, 'getTableHeaders'],
            'permission_callback' => [self::class, 'getTableHeadersPermissionCheck'],
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

    /**
     * Get data table headers
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     */
    public static function getTableHeaders(): WP_Error|WP_REST_Response|WP_HTTP_Response
    {
        $APIData = APIDataRepository::getAPIData();

        return rest_ensure_response($APIData->data->headers);
    }

    /**
     * Allow only backend users to get menus data
     *
     * @return bool
     */
    public static function getTableHeadersPermissionCheck(): bool
    {
        return current_user_can('edit_posts');
    }
}
