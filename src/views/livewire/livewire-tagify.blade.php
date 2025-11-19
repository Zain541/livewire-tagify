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
        <div x-ref="panel" class="tagify__dropdown tagify__dropdown--text absolute left-0 mt-2 px-2 rounded-md" x-show="openDropdown" x-transition.origin.top.left x-on:click.outside="close()"
            style="display: none; !important;">
            <!-- Added flex and flex-col classes -->
            <div class="tagify__dropdown__item flex cursor-pointer px-4 py-2.5 my-1 text-left text-sm hover:bg-gray-200 disabled:text-gray-500 items-center">
                <a x-on:click="deleteTag()"
                    class="gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md mb-2">
                    Delete
                </a>
            </div>

            <div class="flex pl-2 pb-2 my-3" style="flex-wrap: wrap; margin-bottom: 10px">
                @foreach ($configuration['colors'] as $color)
                    <div x-on:click="changeColor('{{ $color }}')" class="cursor-pointer ml-1 mb-1 color-container"
                    style="background: {{ $color }};"></div>
                @endforeach
            </div>


            <!-- Add more flex items here -->
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
