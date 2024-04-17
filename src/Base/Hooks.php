<?php

namespace APIBasedPlugin\Base;

defined('ABSPATH') || exit;

use APIBasedPlugin\Handlers;
use APIBasedPluginBlocks;

/**
 * Hooks functionality for the theme.
 *
 * Run hook handlers
 *
 * @package    API Based
 */
class Hooks
{
    public static function initHooks(): void
    {
        /************************************
         *            Activation
         ************************************/
        register_activation_hook(API_BASED_PLUGIN_FILE, [Handlers\PluginActivation::class, 'activateHandler']);
        register_deactivation_hook(API_BASED_PLUGIN_FILE, [Handlers\PluginActivation::class, 'deactivateHandler']);

        /************************************
         *          Plugin Settings
         ************************************/
        add_action('after_setup_theme', [Handlers\Settings\PluginSettings::class, 'boot']);
        add_action('carbon_fields_register_fields', [Handlers\Settings\PluginSettings::class, 'make']);
        add_filter('plugin_action_links', [Handlers\Settings\PluginSettings::class, 'addActionLinks'], 10, 2);
    }
}
