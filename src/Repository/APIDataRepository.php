<?php

namespace APIBasedPlugin\Repository;

defined('ABSPATH') || exit;

use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Helper\Utils;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Repository for API Data
 *
 * @package    API Based
 */
class APIDataRepository
{
    /**
     * Get API data
     *
     * @return object
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     */
    public static function getAPIData(): object
    {
        $prefix = Config::get('settingsPrefix');

        // Check if the data is already stored in the transient
        $APIData = get_transient($prefix . 'api_data');

        if (!empty($APIData)) {
            return $APIData;
        }

        // If there is no data in the transient, fetch it from the URL
        return self::fetchAPIData();
    }

    /**
     * Fetch API data from external source and save it in transient for 1 hour
     *
     * @return object
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public static function fetchAPIData(): object
    {
        $prefix = Config::get('settingsPrefix');

        $dataSourceUrl = Utils::getOption('data_source_url', '');

        $response = wp_remote_get($dataSourceUrl);

        if (empty($response) || is_wp_error($response)) {
            return new \stdClass();
        }

        $body    = wp_remote_retrieve_body($response);
        $APIData = json_decode($body);

        // Store the data in the transient for 1 hour
        set_transient($prefix . 'api_data', $APIData, HOUR_IN_SECONDS);

        return $APIData;
    }
}
