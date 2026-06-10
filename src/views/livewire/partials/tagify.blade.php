@use(Illuminate\Support\Js)

<div wire:ignore class="livewire-tagify" data-livewire-tagify-frontend="{{ $frontendLibrary }}">
    @php
        $configuration = $this->prepareConfigurations();
        $componentOptions = [
            'defaultColor' => $configuration['default_color'],
            'whitelist' => $this->prepareWhitelist(),
        ];
    @endphp
    <div class="tagify-wrapper"
         style="position:relative"
         wire:key='{{ $componentKey }}'
         x-data="livewireTagify({{ Js::from($componentOptions) }})">

        <input type="text" x-ref="tagInput" value='{{ $this->getModelTags() }}'>

        @include("livewire-tagify::livewire.partials.dropdowns.{$frontendLibrary}")
    </div>

    <style>
        .color-container {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .livewire-tagify {
            padding: 0.75rem;
        }

        .tagify__dropdown {
            background-color: #ffffff;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .tagify {
            --tags-border-color: #d1d5db;
            --tags-hover-border-color: #b0b0b0;

            --tag-text-color: #000000;
            --tag-border-radius: 3px;
            --tag-hover: #dbeafe;

            --tag-remove-bg: #fee2e2;
            --tag-remove-btn-bg--hover: #f87171;

            width: 100%;
            border-radius: 0.375rem;
            padding: 3px 6px;
            border: 1px solid var(--tags-border-color);
        }

        .tagify__dropdown {
            --tags-border-color: #d1d5db;
            --dropdown-radius-outer: 0.5rem;
            --dropdown-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --dropdown-item-bg--hover: #dbeafe;
            --dropdown-item-text--hover: #1d4ed8;

            box-shadow: var(--dropdown-shadow);
            border-radius: var(--dropdown-radius-outer);

            margin-top: 4px !important;
        }

        .tagify__dropdown__wrapper {
            border: 1px solid var(--tags-border-color);
            border-radius: var(--dropdown-radius-outer) !important;
            padding: 4px;
        }

        .tagify__dropdown__item {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 2px;
        }

        .tagify__dropdown__item--active {
            background: var(--dropdown-item-bg--hover) !important;
            color: var(--dropdown-item-text--hover) !important;
        }

        .livewire-tagify__color-button {
            border: 1px solid rgb(17 24 39 / 0.05);
            border-radius: 9999px;
            cursor: pointer;
            display: inline-block;
            height: 1.5rem;
            width: 1.5rem;
        }

        .livewire-tagify__color-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
        }
    </style>
</div>
