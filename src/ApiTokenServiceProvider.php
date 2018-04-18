<?php

namespace UoGSoE\ApiTokenMiddleware;

use Illuminate\Support\ServiceProvider;

class ApiTokenServiceProvider extends ServiceProvider
{
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->publishes([
            __DIR__.'/ApiToken.php' => app_path('ApiToken.php'),
        ]);
        $this->publishes([
            __DIR__.'/../migrations/2018_04_18_090739_create_api_tokens_table.php' =>
                database_path('migrations/2018_04_18_090739_create_api_tokens_table.php'),
        ]);
        $router->aliasMiddleware('apitoken', 'UoGSoE\ApiTokenMiddleware\BasicApiTokenMiddleware');
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ListTokens::class,
                Commands\CreateToken::class,
                Commands\DeleteToken::class,
                Commands\RegenerateToken::class,
            ]);
        }
    }

    public function register()
    {
    }
}
