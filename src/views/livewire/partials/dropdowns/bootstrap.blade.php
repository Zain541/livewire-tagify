@use(Illuminate\Support\Js)

<div x-ref="panel"
     x-show="openDropdown"
     x-transition.opacity.duration.150ms
     x-on:click.outside="close()"
     style="display: none;"
     data-livewire-tagify-dropdown="bootstrap"
     role="dialog"
     aria-label="Tag options"
     class="tagify__dropdown position-absolute start-0 w-100">

    <div class="tagify__dropdown__wrapper">
        <button type="button"
                x-on:click="deleteTag()"
                aria-label="Delete tag permanently"
                class="tagify__dropdown__item d-flex align-items-center gap-2 w-100 text-start border-0 bg-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 16px; height: 16px; opacity: 0.45;" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.1499.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149-.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 001.5.06l.3-7.5z"
                      clip-rule="evenodd"/>
            </svg>
            <span style="font-size: 12px; font-weight: 500;">Delete Tag Permanently</span>
        </button>

        <hr style="margin: 6px 0; opacity: 0.08;" role="separator">

        <div style="padding: 10px 14px;">
            <p class="livewire-tagify__section-label" id="tagify-color-label-bs">
                Label Color
            </p>

            <div class="d-flex flex-wrap" style="gap: 8px;" role="listbox" aria-labelledby="tagify-color-label-bs">
                @foreach ($configuration['colors'] as $colorName => $color)
                    <button type="button"
                            x-on:click="changeColor({{ Js::from($color) }})"
                            role="option"
                            aria-label="Color {{ $colorName }}"
                            class="border-0 p-0 livewire-tagify__color-button"
                            style="background: {{ $color }};"
                            title="{{ $colorName }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
