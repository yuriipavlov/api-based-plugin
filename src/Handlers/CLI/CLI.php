<?php

namespace APIBasedPlugin\Handlers\CLI;

defined('ABSPATH') || exit;

use APIBasedPlugin\Handlers\Errors\ErrorHandler;
use APIBasedPlugin\Helper\Utils;
use Throwable;
use WP_CLI;

/**
 * Container for custom WP-CLI commands
 *
 * @package    API Based
 */
class CLI
{
    /**
     * Adds WP_CLI commands
     *
     * @return void
     * @throws Throwable
     */
    public static function addCommands(): void
    {
        if (!Utils::isDoingWPCLI()) {
            return;
        }

        try {
            WP_CLI::add_command('fetch-api-data', [Commands\RefreshAPIData::class, 'run']);
        } catch (Throwable $throwable) {
            ErrorHandler::handleThrowable($throwable);
        }
    }
}
