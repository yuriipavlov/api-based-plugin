<?php
/**
 * API Based Plugin
 *
 * @package    API Based
 *
 * @wordpress-plugin
 * Plugin Name:       API Based Plugin
 * Plugin URI:        https://github.com/yuriipavlov/api-based-plugin
 * Description:       WordPress simple plugin that retrieves data from a remote API endpoint, and makes that data accessible/retrievable from an API endpoint on the WordPress site your plugin is installed on. The data will be displayed via a custom block and on an admin WordPress page as described.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Yurii Pavlov
 * Author URI:        https://www.linkedin.com/in/yurii-pavlov/
 * Text Domain:       api-based-plugin
 * Domain Path:       /languages
 * License:           MIT
 * License URI:       https://github.com/yuriipavlov/api-based-plugin/blob/master/LICENSE.md
 */

defined('ABSPATH') || exit;

use APIBasedPlugin\App;
use APIBasedPlugin\Handlers\Errors\ErrorHandler;
use Psr\Container\ContainerInterface;

/**
 * Theme bootstrap file
 *
 * @package    API Based
 */

if (PHP_VERSION_ID < 80100) {
    error_log(sprintf(__('Plugin requires at least PHP %s (You are using PHP %s) '), '8.1', PHP_VERSION));
    if (!is_admin()) {
        wp_die(__('Plugin requires a higher PHP Version. Please check the Logs for more details.'));
    }
} else {
    try {
        // helper debug functions for developers
        require_once __DIR__ . '/src/dev.php';
        // psr-4 autoload
        require_once __DIR__ . '/vendor/autoload.php';

        /** @var ContainerInterface $container */
        $container = apply_filters('api_based_plugin/container', require __DIR__ . '/config/container.php');

        App::instance()->run($container);

    } catch (Throwable $throwable) {
        try {
            ErrorHandler::handleThrowable($throwable);
        } catch (Throwable $e) {
            error_log($e);
        }
    }
}
