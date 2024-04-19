<?php

namespace APIBasedPlugin\Handlers\Settings;

defined('ABSPATH') || exit;

use APIBasedPlugin\Helper\Config;
use APIBasedPlugin\Helper\NotFoundException;
use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Theme settings handler
 *
 * @package    API Based
 */
class PluginSettings
{
    /**
     * Connect Carbon Fields
     *
     * @return void
     */
    public static function boot(): void
    {
        Carbon_Fields::boot();
    }

    /**
     * Make Carbon Fields
     *
     * @return void
     * @throws NotFoundException
     */
    public static function make(): void
    {
        $prefix = Config::get('settingsPrefix');

        $container = Container::make(
            'theme_options',  // type
            $prefix . 'plugin_settings', // id
            esc_html__('API Based Plugin Settings', 'api-based-plugin') // desc
        );

        $container->set_page_parent('options-general.php'); // id of the "Appearance" admin section
        $container->set_page_menu_title(esc_html__('API Based Settings', 'api-based-plugin'));


        /** General */
        $container->add_tab(
            __('General', 'api-based-plugin'),
            [
                Field::make(
                    'text',
                    $prefix . 'data_source_url',
                    esc_html__('External data source URL', 'api-based-plugin')
                )
                    ->set_attribute('placeholder', 'https://example.com/v1/endpoint/ ')
                    ->set_width(50),
            ]
        );
    }

    public static function addActionLinks($actions, $pluginFile)
    {
        if (!str_contains($pluginFile, basename(API_BASED_PLUGIN_FILE))) {
            return $actions;
        }

        $prefix = Config::get('settingsPrefix');

        $settingsLink = '<a href="options-general.php?page=crb_carbon_fields_container_' .
                        esc_attr($prefix) . 'plugin_settings.php' . '">' .
                        esc_html__('Settings', 'api-based-plugin') . '</a>';

        array_unshift($actions, $settingsLink);

        return $actions;
    }
}
