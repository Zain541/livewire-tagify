@use(Illuminate\Support\Js)

@php
    $configuration = $this->prepareConfigurations();
    $themeMode = $configuration['theme_mode'] ?? 'light';
    $themeDirection = $configuration['theme_direction'] ?? 'refined';
    $tagShape = $configuration['tag_shape'] ?? 'pill';
    $loadFonts = $configuration['load_fonts'] ?? true;
    $inputPadding = $configuration['input_padding'] ?? null;
    $isDark = $themeMode === 'dark';
    $isAuto = $themeMode === 'auto';
    $darkColors = $configuration['dark_colors'] ?? [];
    $hasDarkColors = !empty($darkColors);
    $activeColors = ($isDark && $hasDarkColors) ? $darkColors : $configuration['colors'];
    $activeDefaultColor = $isDark ? ($configuration['dark_default_color'] ?? $configuration['default_color']) : $configuration['default_color'];

    $componentOptions = [
        'defaultColor' => $activeDefaultColor,
        'whitelist' => $this->prepareWhitelist(),
    ];

    $configuration['colors'] = $activeColors;

    $wrapperClasses = ['livewire-tagify'];
    if ($themeDirection !== 'refined') {
        $wrapperClasses[] = 'livewire-tagify--' . $themeDirection;
    }
    if ($isDark) {
        $wrapperClasses[] = 'livewire-tagify--dark';
    }
@endphp

<div wire:ignore
     class="{{ implode(' ', $wrapperClasses) }}"
     data-livewire-tagify-frontend="{{ $frontendLibrary }}"
     data-tagify-shape="{{ $tagShape }}"
     data-tagify-theme="{{ $themeMode }}"
     @if($inputPadding) style="--tagify-input-padding: {{ $inputPadding }}" @endif>

    @once
    @if($loadFonts)
    @php
        $fontUrls = [
            'refined' => 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap',
            'bold' => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap',
            'glass' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
        ];
    @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontUrls[$themeDirection] ?? $fontUrls['refined'] }}" rel="stylesheet">
    @endif
    <script>
    {!! file_get_contents(dirname((new ReflectionClass(\Codekinz\LivewireTagify\LivewireTagifyServiceProvider::class))->getFileName()) . '/js/livewire-tagify.js') !!}
    </script>
    @endonce

    @if($themeMode === 'auto')
    <script>
    (function(){
        var el = document.currentScript.closest('.livewire-tagify');
        if (!el) return;
        function apply() {
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                el.classList.add('livewire-tagify--dark');
            } else {
                el.classList.remove('livewire-tagify--dark');
            }
        }
        apply();
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', apply);
    })();
    </script>
    @endif

    <div class="tagify-wrapper"
         style="position:relative"
         wire:key='{{ $componentKey }}'
         x-data="livewireTagify({{ Js::from($componentOptions) }})">

        <div aria-live="polite" class="livewire-tagify__sr-only" x-ref="liveRegion"></div>

        <input type="text" x-ref="tagInput" value='{{ $this->getModelTags() }}'>

        @include("livewire-tagify::livewire.partials.dropdowns.{$frontendLibrary}")
    </div>

    {{-- Base styles (always loaded) --}}
    @once
    @include('livewire-tagify::livewire.partials.styles.base')
    @endonce

    {{-- Shape overrides (loaded when not default pill) --}}
    @if($tagShape !== 'pill')
    @once
    @include('livewire-tagify::livewire.partials.styles.shapes')
    @endonce
    @endif

    {{-- Dark mode (loaded when dark or auto) --}}
    @if($themeMode === 'dark' || $themeMode === 'auto')
    @once
    @include('livewire-tagify::livewire.partials.styles.dark')
    @endonce
    @endif

    {{-- Bold direction --}}
    @if($themeDirection === 'bold')
    @once
    @include('livewire-tagify::livewire.partials.styles.bold')
    @endonce
    @endif

    {{-- Glass direction --}}
    @if($themeDirection === 'glass')
    @once
    @include('livewire-tagify::livewire.partials.styles.glass')
    @endonce
    @endif

    {{-- Accessibility (always loaded) --}}
    @once
    @include('livewire-tagify::livewire.partials.styles.accessibility')
    @endonce
</div>
