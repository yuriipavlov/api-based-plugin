<?php

namespace APIBasedPlugin\Handlers\CLI\Commands;

defined('ABSPATH') || exit;

use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Repository\APIDataRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use WP_CLI;

/**
 * WP-CLI Command to refresh API data
 *
 * @package    Starter Kit
 */
class RefreshAPIData implements CLICommandInterface
{
    /**
     * Run the command
     *
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function run(): void
    {
        $APIData = APIDataRepository::fetchAPIData();

        if (empty($APIData)) {
            WP_CLI::error(esc_html__('Fetching data error', 'api-based-plugin'));
        }

        WP_CLI::success(esc_html__('Data has been refreshed!', 'api-based-plugin'));
    }
}
