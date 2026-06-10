<div wire:ignore class="livewire-tagify" data-livewire-tagify-frontend="{{ $frontendLibrary }}">
    @php
        $configuration = $this->prepareConfigurations();
        $whitelistJson = json_encode($this->prepareWhitelist(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
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
            this.tagify.whitelist = this.tagify.whitelist.filter(item => item.id !== this.activeTag.data.id);
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
                this.whitelist = JSON.parse('{{ $whitelistJson }}');
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
