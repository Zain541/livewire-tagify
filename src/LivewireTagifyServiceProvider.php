<?php

namespace Codekinz\LivewireTagify;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Codekinz\LivewireTagify\Components\LivewireTagify;

// namespace WireElements\Pro\Components\Modal\Foundation;


class LivewireTagifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__  .   '/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views', 'livewire-tagify');
        Livewire::component('livewire-tagify', LivewireTagify::class);

        $this->publishes([
            __DIR__.'/Config/livewire-tagify.php' => config_path('livewire-tagify.php'),
            __DIR__.'/Database/Migrations/create_tags_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_tags_table.php'),
        ], 'livewire-tagify');
        //php artisan vendor:publish --provider="LivewireTagify\LivewireTagifyServiceProvider"  --tag=livewire-tagify-config
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/livewire-tagify.php', 'livewire-tagify');
    }

}
