<?php

/**
 * Plugin Uninstall
 *
 * Uninstalling Plugin database data.
 *
 * @package    API Based
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

// Add "_" to settings prefix if we use Carbon Fields
$prefix = "_abp_";

/**
 * Delete plugin options from database
 *
 * @param string $prefix
 */
function uninstallDeleteData(string $prefix): void
{
    global $wpdb;

    $pluginOptions = $wpdb->get_results(
        "SELECT option_name FROM  {$wpdb->prefix}options WHERE option_name LIKE '{$prefix}%' "
    );

    foreach ($pluginOptions as $pluginOption) {
        delete_option($pluginOption->option_name);
    }
}

if (is_multisite()) {
    $sites = get_sites();

    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);
        uninstallDeleteData($prefix);
        restore_current_blog();
    }
} else {
    uninstallDeleteData($prefix);
}

wp_cache_flush();
