<?php

return [
    'config' => [
        'pluginName'          => 'API Based Plugin',
        'package'             => 'API Based',
        'pluginSlug'          => 'api-based-plugin',
        'pluginNamespace'     => 'APIBasedPlugin',
        'hooksPrefix'         => 'api_based_plugin',
        'settingsPrefix'      => 'abp_',
        'restApiNamespace'    => 'abp/v1',
        'blocksDir'           => API_BASED_PLUGIN_DIR . '/blocks/',
        'blocksUri'           => API_BASED_PLUGIN_URL . '/blocks/',
        'blocksIcons'         => '',
        'blocksCategorySlug'  => 'api-based-plugin',
        'blocksCategoryTitle' => 'APIBasedPlugin Blocks',
        'blocksViewDir'       => 'view/',
    ],
];
