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
        $APIData = APIDataRepository::getAPIData();

        // Start building the HTML output
        echo '<div class="wrap">';
        if (!empty($APIData->title)) {
            echo '<h1>' . esc_html($APIData->title) . '</h1>';
        }
        echo '<table class="widefat fixed" cellspacing="0">';
        echo '<thead><tr>';

        // Header row
        if (!empty($APIData->data->headers)) {
            foreach ($APIData->data->headers as $header) {
                echo '<th>' . esc_html($header) . '</th>';
            }
        }
        echo '</tr></thead>';
        echo '<tbody>';

        // Data rows
        if (!empty($APIData->data->rows)) {
            foreach ($APIData->data->rows as $row) {

                echo '<tr>';
                foreach ($row as $colIndex => $colData) {
                    echo '<td>';
                    if ($colIndex === 'date') {
                        echo date('Y-m-d H:i:s', esc_html($colData));
                    } else {
                        echo esc_html($colData);
                    }
                    echo '</td>';
                }
                echo '</tr>';

            }
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }

}
