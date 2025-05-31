<?php

namespace ResolutoDigital\DevChat\Providers;

use Illuminate\Support\ServiceProvider;

class DevChatServiceProviders extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/devchat.php', 'devchat');

        $this->app->singleton(DevChatAgent::class);
        $this->app->singleton(PromptRegistry::class);
        $this->app->singleton(FileWriter::class);
        $this->app->singleton(HistoryLogger::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/devchat.php' => config_path('devchat.php'),
            ], 'config');

            $this->commands([DevChatCommand::class]);
        }
    }
}