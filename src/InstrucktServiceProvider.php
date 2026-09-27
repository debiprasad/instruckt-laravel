<?php

declare(strict_types=1);

namespace Instruckt\Laravel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Instruckt\Laravel\Console\InstallCommand;
use Instruckt\Laravel\Console\UninstallCommand;
use Instruckt\Laravel\Console\UpdateCommand;

final class InstrucktServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/instruckt.php', 'instruckt');
    }

    public function boot(): void
    {
        $this->publishAssets();
        $this->registerHttpRoutes();
        $this->registerMcpRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([InstallCommand::class, UpdateCommand::class, UninstallCommand::class]);
        }
    }

    private function registerHttpRoutes(): void
    {
        if (! config('instruckt.enabled', false)) {
            return;
        }

        Route::middleware(config('instruckt.api_middleware', ['api']))
            ->prefix(config('instruckt.route_prefix', 'instruckt'))
            ->name('instruckt.')
            ->group(function () {
                Route::get('annotations', fn () => 'annotations')->name('annotations.index');
                Route::post('annotations', fn () => 'annotations')->name('annotations.store');
            });
    }

    private function registerMcpRoutes(): void
    {
        if (! config('instruckt.enabled', false)) {
            return;
        }

        if (! class_exists(\Laravel\Mcp\Facades\Mcp::class)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/mcp.php');
    }

    private function publishAssets(): void
    {
        $this->publishes([
            __DIR__ . '/../config/instruckt.php' => config_path('instruckt.php'),
        ], 'instruckt-config');
    }
}
