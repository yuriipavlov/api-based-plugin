<?php

return [
    'config' => [
        'pluginName'          => 'API Based Plugin',
        'package'             => 'API Based',
        'themeSlug'           => 'api-based-plugin',
        'pluginNamespace'     => 'APIBasedPlugin',
        'hooksPrefix'         => 'api_based_plugin',
        'settingsPrefix'      => 'abp_',
        'restApiNamespace'    => 'abp/v1',
        'assetsUri'           => '/assets/',
        'blocksDir'           => get_template_directory() . '/blocks/',
        'blocksIcons'         => '',
        'blocksCategorySlug'  => 'api-based-plugin',
        'blocksCategoryTitle' => 'APIBasedPlugin Blocks',
        'blocksViewDir'       => 'view/',
    ],
];
