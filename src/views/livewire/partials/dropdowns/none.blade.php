@use(Illuminate\Support\Js)

<div x-ref="panel"
     x-show="openDropdown"
     x-transition.opacity.duration.150ms
     x-on:click.outside="close()"
     style="display: none;"
     data-livewire-tagify-dropdown="none"
     class="tagify__dropdown">

    <div class="tagify__dropdown__wrapper">
        <button type="button"
                x-on:click="deleteTag()"
                class="tagify__dropdown__item">
            Delete Tag Permanently
        </button>

        <div class="tagify__dropdown__item">
            <p>
                Label Color
            </p>

            <div class="livewire-tagify__color-list">
                @foreach ($configuration['colors'] as $color)
                    <button type="button"
                            x-on:click="changeColor({{ Js::from($color) }})"
                            class="livewire-tagify__color-button"
                            style="background: {{ $color }};"
                            title="{{ $color }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
