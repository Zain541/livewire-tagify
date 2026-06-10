@use(Illuminate\Support\Js)

<div x-ref="panel"
     x-show="openDropdown"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-1.5"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-1.5"
     x-on:click.outside="close()"
     style="display: none;"
     data-livewire-tagify-dropdown="tailwind"
     role="dialog"
     aria-label="Tag options"
     class="tagify__dropdown absolute left-0 z-50 w-full">

    <div class="tagify__dropdown__wrapper">
        <button x-on:click="deleteTag()"
                aria-label="Delete tag permanently"
                class="tagify__dropdown__item w-full text-left flex items-center gap-2.5 group">

            <div class="text-gray-400 group-hover:text-red-500 transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.1499.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149-.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 001.5.06l.3-7.5z"
                          clip-rule="evenodd"/>
                </svg>
            </div>

            <span class="text-xs font-medium text-gray-500 group-hover:text-red-500 transition-colors duration-150">
                Delete Tag Permanently
            </span>
        </button>

        <div class="h-px w-full bg-gray-100 my-1.5" role="separator"></div>

        <div class="px-3.5 py-2.5">
            <p class="livewire-tagify__section-label" id="tagify-color-label">
                Label Color
            </p>

            <div class="flex flex-wrap gap-2" role="listbox" aria-labelledby="tagify-color-label">
                @foreach ($configuration['colors'] as $colorName => $color)
                    <button x-on:click="changeColor({{ Js::from($color) }})"
                            role="option"
                            aria-label="Color {{ $colorName }}"
                            class="w-8 h-8 rounded-lg border-2 border-transparent hover:border-gray-900 hover:scale-110 transition-all duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
                            style="background: {{ $color }}; box-shadow: 0 1px 2px rgba(0,0,0,0.06);"
                            title="{{ $colorName }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
