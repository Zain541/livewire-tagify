<div wire:ignore class="p-3">
    @php
        $configuration = $this->prepareConfigurations();
    @endphp
    <div class="tagify-wrapper" style="position:relative" wire:key='{{ $componentKey }}' x-data="{
        tagify: null,
        openDropdown: false,
        defaultColor: '{{ $configuration['default_color'] }}',
        activeTag: null,
        tagInput: null,
        whitelist: [],

        initTagify: function() {

            let transformTag = (tagData) => {
                var color = this.defaultColor;
                if (tagData.hasOwnProperty('color')) {
                    color = tagData.color;
                }
                tagData.style = '--tag-bg:' + color;

            }
            return new Tagify(this.tagInput, {
                whitelist: [],
                transformTag: transformTag,
                dropdown: {
                    enabled: 0
                }
            });
        },
        toggle: function() {
            if (this.openDropdown) {
                return this.close()
            }
            this.openDropdown = true
        },
        changeColor: function(color) {
            const { tag: tagElm, data: tagData } = this.activeTag;
            tagData.color = color;
            tagData.style = '--tag-bg:' + tagData.color;
            this.tagify.replaceTag(tagElm, tagData);
            this.openDropdown = false;
            Livewire.emit('changeColorTagEvent', this.activeTag.data.value, this.activeTag.data.type, color);
        },
        deleteTag: function() {
            Livewire.emit('deleteTagEvent', this.activeTag.data.id);
            this.tagify.removeTags(this.activeTag.data.value)
            this.tagify.whitelist = this.tagify.whitelist.filter(item => item.id != this.activeTag.id);
            this.openDropdown = false;
        },
        close: function() {
            if (!this.openDropdown) return
            this.openDropdown = false
        },

        init() {
            this.$nextTick(() => {

                this.tagInput = this.$refs.tagInput;
                this.tagify = this.initTagify();
                this.whitelist = [{!! $this->prepareWhitelist() !!}];
                this.tagify.whitelist = this.whitelist;

                let onTagEdit = (e) => {
                    var updatedValue = e.detail.data.value;
                    var updatedTagId = e.detail.data.id;
                    var oldValue = e.detail.previousData.__originalData.value;
                    this.tagify.whitelist[this.tagify.whitelist.indexOf(oldValue)] = updatedValue;
                    Livewire.emit('editTagEvent', e.detail.data);
                    this.tagify.whitelist = this.tagify.whitelist.map(item => {
                        if (item.id === updatedTagId) {
                            return { ...item, value: updatedValue };
                        }
                        return item;
                    });

                }
                let onTagClick = (e) => {
                    this.activeTag = e.detail;
                    this.toggle();
                }

                let onAddTag = (e) => {
                    Livewire.emit('addNewTagEvent', e.detail.tagify.value);
                    
                    const value = e.detail.data.value;
                    if (!this.tagify.whitelist.some(item => item.value === value)) {
                        this.tagify.whitelist.push({ value, color: this.defaultColor });
                    }
                }

                let onRemoveTag = (e) => {
                    Livewire.emit('removeTagEvent', e.detail.data);
                }

                this.tagify.on('add', onAddTag)
                    .on('remove', onRemoveTag)
                    .on('edit:updated', onTagEdit)
                    .on('click', onTagClick);

            });
        }
    }">

        <input type="text" x-ref="tagInput" value='{{ $this->getModelTags() }}'>
        <div x-ref="panel"
             x-show="openDropdown"
             x-transition.opacity.duration.150ms
             x-on:click.outside="close()"
             style="display: none;"
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
                        Delete Tag
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
                                    class="w-6 h-6 rounded-full shadow-sm ring-1 ring-gray-900/5 
                                 hover:scale-110 hover:ring-2 hover:ring-offset-1 hover:ring-[var(--dropdown-item-text--hover)] 
                                 transition-all duration-200 ease-out focus:outline-none"
                                    style="background: {{ $color }};"
                                    title="{{ $color }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-container {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .tagify__dropdown {
            background-color: #ffffff;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .tagify {
            --tags-border-color: #d1d5db; /* input border color */
            --tags-hover-border-color: #b0b0b0; /* input border color on hover */

            --tag-text-color: #000000; /* tags text color */
            --tag-border-radius: 3px; /* tags border radius */
            --tag-hover: #dbeafe; /* tags background hover color */

            --tag-remove-bg: #fee2e2; /* tags background color on delete icon */
            --tag-remove-btn-bg--hover: #f87171; /* delete icon background color on delete icon hover */

            width: 100%;
            border-radius: 0.375rem;
            padding: 3px 6px;
            border: 1px solid var(--tags-border-color);
        }

        .tagify__dropdown {
            --tags-border-color: #d1d5db;           /* dropdown border color */
            --dropdown-radius-outer: 0.5rem;        /* dropdown border radius */
            --dropdown-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --dropdown-item-bg--hover: #dbeafe;     /* dropdown item background color on hover */ 
            --dropdown-item-text--hover: #1d4ed8;   /* dropdown item text color on hover */

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
    </style>
</div>
