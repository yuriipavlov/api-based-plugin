<?php

namespace APIBasedPlugin\Handlers;

defined('ABSPATH') || exit;

use Exception;
use PHPMailer;
use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Helper\Utils;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Front End handler
 *
 * @package    API Based
 */
class Front
{

    /**
     * Load additional JS data variables
     *
     * @return void
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function loadFrontendJsData(): void
    {
        $prefix = Config::get('settingsPrefix');
        $pluginSlug = Config::get('pluginSlug');
        $pluginNamespace = Config::get('pluginNamespace');

        wp_register_script($pluginSlug . '-front-vars', '', [], '', true);
        wp_enqueue_script($pluginSlug . '-front-vars');
        $frontendData = [
            'restApiUrl'    => get_rest_url(),
            'restNamespace' => Config::get('restApiNamespace'),
            'restNonce'     => wp_create_nonce('wp_rest'),
        ];

        wp_localize_script($pluginSlug . '-front-vars', $pluginNamespace . 'FrontendData', $frontendData);
    }
}
