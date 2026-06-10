<?php

namespace Codekinz\LivewireTagify\Traits;

trait ResolvesTagifyConfiguration
{
    protected ?array $preparedConfigurations = null;

    public function prepareConfigurations(): array
    {
        return $this->preparedConfigurations ??= $this->resolveConfigurations();
    }

    protected function resolveConfigurations(): array
    {
        $defaults = [
            'colors' => config('livewire-tagify.colors', []),
            'default_color' => config('livewire-tagify.default_color', 'lightgray'),
            'frontend_library' => config('livewire-tagify.frontend_library', 'tailwind'),
        ];

        return array_merge($defaults, $this->validConfigurationOverrides($this->configurations()));
    }

    protected function validConfigurationOverrides(array $configurations): array
    {
        $overrides = [];

        if (! empty($configurations['colors']) && is_array($configurations['colors'])) {
            $overrides['colors'] = $configurations['colors'];
        }

        if (! empty($configurations['default_color'])) {
            $overrides['default_color'] = $configurations['default_color'];
        }

        if (! empty($configurations['frontend_library'])) {
            $overrides['frontend_library'] = $configurations['frontend_library'];
        }

        return $overrides;
    }

    public function frontendLibrary(): string
    {
        $frontendLibrary = $this->preparedConfiguration('frontend_library', 'tailwind');

        return in_array($frontendLibrary, ['tailwind', 'bootstrap', 'none'], true) ? $frontendLibrary : 'tailwind';
    }

    public function frontendView(): string
    {
        $frontendLibrary = $this->frontendLibrary();
        $frontendViews = config('livewire-tagify.frontend_views', []);

        return $frontendViews[$frontendLibrary] ?? 'livewire-tagify::livewire.tailwind';
    }

    protected function configurations(): array
    {
        return [];
    }

    /**
     * @param  mixed  $default
     * @return mixed
     */
    protected function preparedConfiguration(string $key, $default = null)
    {
        return $this->prepareConfigurations()[$key] ?? $default;
    }
}
