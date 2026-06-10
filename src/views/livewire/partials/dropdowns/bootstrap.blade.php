<div x-ref="panel"
     x-show="openDropdown"
     x-transition.opacity.duration.150ms
     x-on:click.outside="close()"
     style="display: none;"
     data-livewire-tagify-dropdown="bootstrap"
     class="tagify__dropdown position-absolute start-0 w-100">

    <div class="tagify__dropdown__wrapper bg-white">
        <button type="button"
                x-on:click="deleteTag()"
                class="tagify__dropdown__item btn btn-light text-danger d-flex align-items-center gap-2 w-100 text-start">
            Delete Tag
        </button>

        <hr class="my-1">

        <div class="px-3 py-2">
            <p class="small text-uppercase text-muted fw-bold mb-2">
                Label Color
            </p>

            <div class="d-flex flex-wrap gap-2">
                @foreach ($configuration['colors'] as $color)
                    <button type="button"
                            x-on:click="changeColor('{{ $color }}')"
                            class="btn rounded-circle border shadow-sm p-0"
                            style="background: {{ $color }}; width: 1.5rem; height: 1.5rem;"
                            title="{{ $color }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
