<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Enable instruckt
    |--------------------------------------------------------------------------
    | Defaults to true only in local environments. Set INSTRUCKT_ENABLED=true
    | to enable on staging/preview, or INSTRUCKT_ENABLED=false to disable.
    */
    'enabled' => (bool) env('INSTRUCKT_ENABLED', env('APP_ENV') === 'local'),

    'route_prefix' => env('INSTRUCKT_ROUTE_PREFIX', 'instruckt'),

    'api_middleware' => explode(',', env('INSTRUCKT_MIDDLEWARE', 'api')),
    'mcp_middleware' => explode(',', env('INSTRUCKT_MCP_MIDDLEWARE', 'web')),

    'mcp_token' => env('INSTRUCKT_MCP_TOKEN', null),

    'store' => env('INSTRUCKT_STORE', env('APP_ENV') === 'local' ? 'file' : 'database'),

    'screenshot_disk' => env('INSTRUCKT_SCREENSHOT_DISK', 'local'),

    'cdn_url' => env('INSTRUCKT_CDN_URL', null),

    'colors' => [
        // 'default'    => '#6366f1',
        // 'screenshot' => '#22c55e',
        // 'dismissed'  => '#71717a',
    ],

    'keys' => [
        // 'annotate'   => 'a',
        // 'freeze'     => 'f',
        // 'screenshot' => 'c',
        // 'clearPage'  => 'x',
    ],

    'tools' => [
        'annotate' => true,
        'screenshot' => true,
        'freeze' => true,
        'copy' => true,
        'clear_page' => true,
        'clear_all' => true,
        'minimize' => true,
    ],

    'mcp_prefix' => 'instruckt',
];
