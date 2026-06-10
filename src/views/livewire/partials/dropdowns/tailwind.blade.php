<div x-ref="panel"
     x-show="openDropdown"
     x-transition.opacity.duration.150ms
     x-on:click.outside="close()"
     style="display: none;"
     data-livewire-tagify-dropdown="tailwind"
     class="tagify__dropdown absolute left-0 z-50 w-full">

    <div class="tagify__dropdown__wrapper">
        <button x-on:click="deleteTag()"
                class="tagify__dropdown__item w-full text-left flex items-center gap-2.5 group transition-colors duration-150 hover:bg-[var(--tag-remove-bg)]">

            <div class="text-gray-400 group-hover:text-[var(--tag-remove-btn-bg--hover)] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path fill-rule="evenodd"
                          d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.1499.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149-.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 001.5.06l.3-7.5z"
                          clip-rule="evenodd"/>
                </svg>
            </div>

            <span class="font-medium text-xs text-gray-600 group-hover:text-[var(--tag-remove-btn-bg--hover)]">
                Delete Tag Permanently
            </span>
        </button>

        <div class="h-px w-full bg-gray-100 my-1"></div>

        <div class="px-3 py-2">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">
                Label Color
            </p>

            <div class="flex flex-wrap gap-1.5">
                @foreach ($configuration['colors'] as $color)
                    <button x-on:click="changeColor('{{ $color }}')"
                            class="w-6 h-6 rounded-full shadow-sm ring-1 ring-gray-900/5 hover:scale-110 hover:ring-2 hover:ring-offset-1 hover:ring-[var(--dropdown-item-text--hover)] transition-all duration-200 ease-out focus:outline-none"
                            style="background: {{ $color }};"
                            title="{{ $color }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
