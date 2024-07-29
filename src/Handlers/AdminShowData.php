<?php

namespace APIBasedPlugin\Handlers;

use APIBasedPlugin\Helper\NotFoundException;
use APIBasedPlugin\Repository\APIDataRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

defined('ABSPATH') || exit;

/**
 * Show API Data in admin
 *
 * @package    API Based
 */
class AdminShowData
{
    public static function addPageToMenu(): void
    {
        add_menu_page(
            'API Data Table',
            'API Data',
            'manage_options',
            'abp_api_data_table',
            [self::class, 'APIDataTablePage'],
            'dashicons-list-view'
        );
    }

    /**
     * Render admin page with API data table
     *
     * @return void
     *
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function APIDataTablePage(): void
    {
        // Check if the button is pressed
        if (
            isset($_POST['refresh_api_data']) &&
            check_admin_referer('refresh_api_data_action', 'refresh_api_data_field')
        ) {
            APIDataRepository::fetchAPIData();
            echo '<p>' . esc_html__('Data has been refreshed!', 'api-based-plugin') . '</p>';
        }

        $APIData = APIDataRepository::getAPIData();

        // Start building the HTML output
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('API Data', 'api-based-plugin') . '</h1>';
        // Check if the data is loaded successfully
        if ($APIData->status === 'OK' && !empty($APIData->data)) {
            echo '<table class="widefat fixed" cellspacing="0">';
            echo '<thead><tr>';
            // Header row
            echo '<th>' . esc_html__('ID', 'api-based-plugin') . '</th>';
            echo '<th>' . esc_html__('First Name', 'api-based-plugin') . '</th>';
            echo '<th>' . esc_html__('Last Name', 'api-based-plugin') . '</th>';
            echo '<th>' . esc_html__('Email', 'api-based-plugin') . '</th>';
            echo '<th>' . esc_html__('Date', 'api-based-plugin') . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            // Data rows
            foreach ($APIData->data as $item) {
                echo '<tr>';
                echo '<td>' . esc_html($item->id) . '</td>';
                echo '<td>' . esc_html($item->firstName) . '</td>';
                echo '<td>' . esc_html($item->lastName) . '</td>';
                echo '<td>' . esc_html($item->email) . '</td>';
                echo '<td>' . esc_html($item->date) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Failed to load data</p>';
        }

        // Form for refreshing data
        echo '<form method="post" action="">';
        wp_nonce_field('refresh_api_data_action', 'refresh_api_data_field');
        echo '<p class="submit">';
        echo '<input type="submit" name="refresh_api_data" class="button button-primary" value="' .
            esc_html__('Refresh Data', 'api-based-plugin') .
            '">';
        echo '</p>';
        echo '</form>';

        echo '</div>';
    }
}
