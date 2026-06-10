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
            'default_color' => config('livewire-tagify.default_color', '#2B7CD1'),
            'dark_colors' => config('livewire-tagify.dark_colors', []),
            'dark_default_color' => config('livewire-tagify.dark_default_color', '#60A5FA'),
            'frontend_library' => config('livewire-tagify.frontend_library', 'tailwind'),
            'theme_mode' => config('livewire-tagify.theme_mode', 'light'),
            'theme_direction' => config('livewire-tagify.theme_direction', 'refined'),
            'tag_shape' => config('livewire-tagify.tag_shape', 'pill'),
            'load_fonts' => config('livewire-tagify.load_fonts', true),
            'input_padding' => config('livewire-tagify.input_padding'),
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

        if (! empty($configurations['theme_mode']) && in_array($configurations['theme_mode'], ['light', 'dark', 'auto'], true)) {
            $overrides['theme_mode'] = $configurations['theme_mode'];
        }

        if (! empty($configurations['theme_direction']) && in_array($configurations['theme_direction'], ['refined', 'bold', 'glass'], true)) {
            $overrides['theme_direction'] = $configurations['theme_direction'];
        }

        if (! empty($configurations['tag_shape']) && in_array($configurations['tag_shape'], ['pill', 'rounded', 'square'], true)) {
            $overrides['tag_shape'] = $configurations['tag_shape'];
        }

        if (array_key_exists('load_fonts', $configurations)) {
            $overrides['load_fonts'] = (bool) $configurations['load_fonts'];
        }

        if (! empty($configurations['input_padding']) && is_string($configurations['input_padding'])) {
            $overrides['input_padding'] = $configurations['input_padding'];
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

    protected function preparedConfiguration(string $key, mixed $default = null): mixed
    {
        return $this->prepareConfigurations()[$key] ?? $default;
    }
}
