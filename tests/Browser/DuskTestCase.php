<?php

namespace Codekinz\LivewireTagify\Tests\Browser;

use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Dusk\TestCase;
use Spatie\Tags\TagsServiceProvider;
use Codekinz\LivewireTagify\LivewireTagifyServiceProvider;
use Codekinz\LivewireTagify\Tests\Support\TestTagComponent;
use Livewire\Livewire;

class DuskTestCase extends TestCase
{
    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/../../database.sqlite';

        if (!file_exists($dbFile)) {
            touch($dbFile);
        }

        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            TagsServiceProvider::class,
            LivewireTagifyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => __DIR__ . '/../../database.sqlite',
            'prefix'   => '',
        ]);

        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYfiBfCuSThJ5SK8=');
        $app['config']->set('app.debug', true);

        $configFile = __DIR__ . '/../../src/Config/livewire-tagify.php';
        if (file_exists($configFile)) {
            $app['config']->set('livewire-tagify', require $configFile);
        }

        $app['config']->set('view.paths', [
            realpath(__DIR__ . '/../resources/views'),
            resource_path('views'),
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../src/Database/Migrations');

        Schema::dropIfExists('test_models');

        Schema::create('test_models', function ($table) {
            $table->id();
            $table->string('name');
        });
    }

    protected function defineWebRoutes($router)
    {
        Livewire::component('test-tag-component', TestTagComponent::class);

        $router->get('/dusk-test', function () {
            return view('test-layout');
        });
    }
}
