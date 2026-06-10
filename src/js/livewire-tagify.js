(function (window, document) {
    'use strict';

    function normalizeTag(tag, defaultColor) {
        var value = tag && typeof tag.value === 'string' ? tag.value : '';

        return {
            id: tag && Object.prototype.hasOwnProperty.call(tag, 'id') ? tag.id : null,
            value: value,
            type: tag && Object.prototype.hasOwnProperty.call(tag, 'type') ? tag.type : null,
            color: tag && tag.color ? tag.color : defaultColor,
            style: '--tag-bg:' + (tag && tag.color ? tag.color : defaultColor),
        };
    }

    function sameTag(left, right) {
        if (left.id !== null && right.id !== null) {
            return String(left.id) === String(right.id);
        }

        return left.value === right.value;
    }

    function findTagIndex(whitelist, tag) {
        for (var index = 0; index < whitelist.length; index++) {
            if (sameTag(whitelist[index], tag)) {
                return index;
            }
        }

        return -1;
    }

    function createComponent(options) {
        options = options || {};

        return {
            tagify: null,
            openDropdown: false,
            defaultColor: options.defaultColor || 'lightgray',
            activeTag: null,
            tagInput: null,
            whitelist: [],
            error: null,
            suppressEvents: false,

            init: function () {
                this.whitelist = this.normalizeWhitelist(options.whitelist || []);

                this.$nextTick(function () {
                    this.tagInput = this.$refs.tagInput;

                    if (!this.hasTagify()) {
                        return;
                    }

                    this.tagify = this.initTagify();
                    this.syncTagifyWhitelist();
                    this.bindTagifyEvents();
                }.bind(this));
            },

            hasTagify: function () {
                if (typeof window.Tagify === 'function') {
                    return true;
                }

                this.fail('Tagify is not loaded. Load @yaireo/tagify before initializing livewire-tagify.');

                return false;
            },

            wire: function () {
                if (this.$wire) {
                    return this.$wire;
                }

                this.fail('Livewire is not available for livewire-tagify actions.');

                return null;
            },

            fail: function (message) {
                this.error = message;

                if (window.console && typeof window.console.warn === 'function') {
                    window.console.warn(message);
                }
            },

            callWire: function (action, args, onSuccess, onFailure) {
                var wire = this.wire();

                if (!wire || typeof wire[action] !== 'function') {
                    if (typeof onFailure === 'function') {
                        onFailure.call(this);
                    }

                    return;
                }

                try {
                    Promise.resolve(wire[action].apply(wire, args || []))
                        .then(function (result) {
                            if (result === false) {
                                if (typeof onFailure === 'function') {
                                    onFailure.call(this);
                                }

                                return;
                            }

                            if (typeof onSuccess === 'function') {
                                onSuccess.call(this, result);
                            }
                        }.bind(this))
                        .catch(function (error) {
                            this.fail(error && error.message ? error.message : 'Livewire tag action failed.');

                            if (typeof onFailure === 'function') {
                                onFailure.call(this);
                            }
                        }.bind(this));
                } catch (error) {
                    this.fail(error && error.message ? error.message : 'Livewire tag action failed.');

                    if (typeof onFailure === 'function') {
                        onFailure.call(this);
                    }
                }
            },

            normalizeWhitelist: function (tags) {
                return tags.map(function (tag) {
                    return normalizeTag(tag, this.defaultColor);
                }.bind(this)).filter(function (tag) {
                    return tag.value !== '';
                });
            },

            initTagify: function () {
                return new window.Tagify(this.tagInput, {
                    whitelist: [],
                    transformTag: function (tagData) {
                        var tag = normalizeTag(tagData, this.defaultColor);
                        tagData.color = tag.color;
                        tagData.style = tag.style;
                    }.bind(this),
                    dropdown: {
                        enabled: 0,
                    },
                });
            },

            bindTagifyEvents: function () {
                this.tagify
                    .on('add', this.onAddTag.bind(this))
                    .on('remove', this.onRemoveTag.bind(this))
                    .on('edit:updated', this.onTagEdit.bind(this))
                    .on('click', this.onTagClick.bind(this));
            },

            syncTagifyWhitelist: function () {
                if (this.tagify) {
                    this.tagify.whitelist = this.whitelist.slice();
                }
            },

            upsertWhitelistTag: function (tag) {
                var normalized = normalizeTag(tag, this.defaultColor);
                var index = findTagIndex(this.whitelist, normalized);

                if (index === -1) {
                    this.whitelist.push(normalized);
                } else {
                    this.whitelist[index] = Object.assign({}, this.whitelist[index], normalized);
                }

                this.syncTagifyWhitelist();
            },

            removeWhitelistTag: function (tag) {
                var normalized = normalizeTag(tag, this.defaultColor);

                this.whitelist = this.whitelist.filter(function (item) {
                    return !sameTag(item, normalized);
                });

                this.syncTagifyWhitelist();
            },

            activeTagData: function () {
                if (!this.activeTag || !this.activeTag.data || !this.activeTag.data.value) {
                    this.fail('No active tag is selected.');

                    return null;
                }

                return this.activeTag.data;
            },

            toggle: function () {
                if (this.openDropdown) {
                    return this.close();
                }

                this.openDropdown = true;
            },

            close: function () {
                if (!this.openDropdown) {
                    return;
                }

                this.openDropdown = false;
            },

            changeColor: function (color) {
                var tagData = this.activeTagData();

                if (!tagData || !this.activeTag.tag) {
                    return;
                }

                var updatedTag = Object.assign({}, tagData, {
                    color: color,
                    style: '--tag-bg:' + color,
                });

                this.callWire('changeColorTag', [tagData.value, color], function () {
                    this.suppressEvents = true;
                    this.tagify.replaceTag(this.activeTag.tag, updatedTag);
                    this.suppressEvents = false;

                    this.upsertWhitelistTag(updatedTag);
                    this.close();
                });
            },

            deleteTag: function () {
                var tagData = this.activeTagData();

                if (!tagData || !tagData.id) {
                    this.fail('The active tag cannot be deleted because it has no tag ID.');

                    return;
                }

                this.callWire('deleteTag', [tagData.id], function () {
                    this.suppressEvents = true;
                    this.tagify.removeTags(tagData.value);
                    this.suppressEvents = false;

                    this.removeWhitelistTag(tagData);
                    this.close();
                });
            },

            onTagEdit: function (event) {
                if (this.suppressEvents) {
                    return;
                }

                var data = event.detail.data || {};
                var previousData = event.detail.previousData || {};
                var originalData = previousData.__originalData || previousData;
                var oldTag = normalizeTag(originalData, this.defaultColor);
                var updatedTag = normalizeTag(data, this.defaultColor);

                this.callWire('editTag', [data], function () {
                    this.upsertWhitelistTag(updatedTag);
                }, function () {
                    this.suppressEvents = true;

                    if (event.detail.tag) {
                        this.tagify.replaceTag(event.detail.tag, oldTag);
                    } else {
                        this.tagify.removeTags(updatedTag.value);
                        this.tagify.addTags([oldTag]);
                    }

                    this.suppressEvents = false;
                    this.upsertWhitelistTag(oldTag);
                });
            },

            onTagClick: function (event) {
                this.activeTag = event.detail;

                if (!this.activeTagData()) {
                    return this.close();
                }

                this.toggle();
            },

            onAddTag: function (event) {
                if (this.suppressEvents) {
                    return;
                }

                var tags = this.normalizeWhitelist(event.detail.tagify.value || []);

                this.callWire('addNewTag', [event.detail.tagify.value], function () {
                    tags.forEach(function (tag) {
                        this.upsertWhitelistTag(tag);
                    }.bind(this));
                }, function () {
                    this.suppressEvents = true;

                    tags.forEach(function (tag) {
                        this.tagify.removeTags(tag.value);
                    }.bind(this));

                    this.suppressEvents = false;
                });
            },

            onRemoveTag: function (event) {
                if (this.suppressEvents) {
                    return;
                }

                var removedTag = normalizeTag(event.detail.data, this.defaultColor);

                this.callWire('removeTag', [event.detail.data], null, function () {
                    this.suppressEvents = true;
                    this.tagify.addTags([removedTag]);
                    this.suppressEvents = false;
                    this.upsertWhitelistTag(removedTag);
                });
            },
        };
    }

    window.livewireTagify = createComponent;

    document.addEventListener('alpine:init', function () {
        if (window.Alpine && typeof window.Alpine.data === 'function') {
            window.Alpine.data('livewireTagify', createComponent);
        }
    });
})(window, document);
