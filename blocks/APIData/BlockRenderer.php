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
            'permission_callback' => [self::class, 'getNoncePermissionCheck'],
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
        $params = $request->get_params();

        $templateData = [];

        $APIData = APIDataRepository::getAPIData();

        $templateData['apiData'] = $APIData;
        $templateData['columns'] = [];

        if (!empty($params['columns'])) {
            $columnsData             = urldecode($params['columns']);
            $templateData['columns'] = json_decode(base64_decode($columnsData), true);
        }

        asort($templateData['columns']);

        $response = [
            'code' => 'success',
            'data' => self::loadBlockView('layout', $templateData),
        ];

        return new WP_REST_Response($response, 200);
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
     * Check nonce
     *
     * @param $request
     *
     * @return bool|int
     */
    public static function getNoncePermissionCheck($request): bool
    {
        return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest');
    }

    /**
     * Allow only backend users to get headers data
     *
     * @return bool
     */
    public static function getTableHeadersPermissionCheck(): bool
    {
        return current_user_can('edit_posts');
    }
}
