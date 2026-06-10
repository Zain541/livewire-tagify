<?php

namespace Codekinz\LivewireTagify;

use Codekinz\LivewireTagify\Components\LivewireTagify;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireTagifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'livewire-tagify');
        Livewire::component('livewire-tagify', LivewireTagify::class);

        $this->publishes([
            __DIR__.'/Config/livewire-tagify.php' => config_path('livewire-tagify.php'),
            __DIR__.'/Database/Migrations/create_tags_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_tags_table.php'),
            __DIR__.'/js/livewire-tagify.js' => public_path('vendor/livewire-tagify/livewire-tagify.js'),
        ], 'livewire-tagify');

        $this->publishes([
            __DIR__.'/Config/livewire-tagify.php' => config_path('livewire-tagify.php'),
        ], 'livewire-tagify-config');

        $this->publishes([
            __DIR__.'/Database/Migrations/create_tags_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_tags_table.php'),
        ], 'livewire-tagify-migrations');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/livewire-tagify'),
        ], ['livewire-tagify', 'livewire-tagify-views']);

        $this->publishes([
            __DIR__.'/js/livewire-tagify.js' => public_path('vendor/livewire-tagify/livewire-tagify.js'),
        ], ['livewire-tagify', 'livewire-tagify-assets']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/Config/livewire-tagify.php', 'livewire-tagify');
    }
}
