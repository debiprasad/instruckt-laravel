<?php

declare(strict_types=1);

use Instruckt\Laravel\Http\Middleware\ValidateMcpToken;
use Instruckt\Laravel\Mcp\InstrucktServer;
use Laravel\Mcp\Facades\Mcp;

if (config('instruckt.enabled', true)) {
    Mcp::local('instruckt', InstrucktServer::class);

    $mcpMiddleware = array_merge(
        config('instruckt.mcp_middleware', ['web']),
        [ValidateMcpToken::class],
    );

    Mcp::web('/instruckt/mcp', InstrucktServer::class)
        ->middleware($mcpMiddleware);
}
