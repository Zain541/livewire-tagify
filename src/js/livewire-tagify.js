(function (window, document) {
    'use strict';

    function hexToRgb(color) {
        var r = parseInt(color.slice(1, 3), 16);
        var g = parseInt(color.slice(3, 5), 16);
        var b = parseInt(color.slice(5, 7), 16);
        return { r: r, g: g, b: b };
    }

    function getLuminance(r, g, b) {
        var rs = r / 255, gs = g / 255, bs = b / 255;
        rs = rs <= 0.03928 ? rs / 12.92 : Math.pow((rs + 0.055) / 1.055, 2.4);
        gs = gs <= 0.03928 ? gs / 12.92 : Math.pow((gs + 0.055) / 1.055, 2.4);
        bs = bs <= 0.03928 ? bs / 12.92 : Math.pow((bs + 0.055) / 1.055, 2.4);
        return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
    }

    function getContrastColor(color) {
        var rgb = hexToRgb(color);
        return getLuminance(rgb.r, rgb.g, rgb.b) > 0.5 ? '#1a1a1a' : '#ffffff';
    }

    function computeTagStyle(color, direction, isDark) {
        var rgb = hexToRgb(color);
        var r = rgb.r, g = rgb.g, b = rgb.b;

        if (direction === 'bold') {
            var textColor = getContrastColor(color);
            return '--tag-bg:' + color + ';--tag-border-color:' + color + ';--tag-color:' + color + ';--tag-text-color:' + textColor;
        }

        if (direction === 'glass') {
            var bgAlpha = isDark ? 0.22 : 0.14;
            var borderAlpha = isDark ? 0.12 : 0.08;
            var glassBg = 'rgba(' + r + ',' + g + ',' + b + ',' + bgAlpha + ')';
            var glassBorder = isDark ? 'rgba(255,255,255,' + borderAlpha + ')' : 'rgba(0,0,0,' + borderAlpha + ')';
            return '--tag-bg:' + glassBg + ';--tag-border-color:' + glassBorder + ';--tag-color:' + color;
        }

        var tintAlpha = isDark ? 0.18 : 0.1;
        var borderBgAlpha = isDark ? 0.25 : 0.18;
        var tintBg = 'rgba(' + r + ',' + g + ',' + b + ',' + tintAlpha + ')';
        var tintBorder = 'rgba(' + r + ',' + g + ',' + b + ',' + borderBgAlpha + ')';
        return '--tag-bg:' + tintBg + ';--tag-border-color:' + tintBorder + ';--tag-color:' + color;
    }

    function detectTheme(el) {
        var wrapper = el;
        while (wrapper && !wrapper.classList.contains('livewire-tagify')) {
            wrapper = wrapper.parentElement;
        }
        if (!wrapper) return { direction: 'refined', isDark: false };

        var direction = 'refined';
        if (wrapper.classList.contains('livewire-tagify--bold')) direction = 'bold';
        else if (wrapper.classList.contains('livewire-tagify--glass')) direction = 'glass';

        var isDark = wrapper.classList.contains('livewire-tagify--dark');
        return { direction: direction, isDark: isDark };
    }

    function normalizeTag(tag, defaultColor, direction, isDark) {
        var value = tag && typeof tag.value === 'string' ? tag.value : '';
        var color = tag && tag.color ? tag.color : defaultColor;

        return {
            id: tag && Object.prototype.hasOwnProperty.call(tag, 'id') ? tag.id : null,
            value: value,
            type: tag && Object.prototype.hasOwnProperty.call(tag, 'type') ? tag.type : null,
            color: color,
            style: computeTagStyle(color, direction, isDark),
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
            defaultColor: options.defaultColor || '#2B7CD1',
            activeTag: null,
            tagInput: null,
            whitelist: [],
            error: null,
            suppressEvents: false,
            themeDirection: 'refined',
            themeDark: false,

            init: function () {
                var theme = detectTheme(this.$el);
                this.themeDirection = theme.direction;
                this.themeDark = theme.isDark;

                this.whitelist = this.normalizeWhitelist(options.whitelist || []);

                this.$nextTick(function () {
                    this.tagInput = this.$refs.tagInput;

                    if (!this.hasTagify()) {
                        return;
                    }

                    this.tagify = this.initTagify();
                    this.syncTagifyWhitelist();
                    this.bindTagifyEvents();
                    this.observeThemeChanges();
                }.bind(this));
            },

            observeThemeChanges: function () {
                var wrapper = this.$el;
                while (wrapper && !wrapper.classList.contains('livewire-tagify')) {
                    wrapper = wrapper.parentElement;
                }
                if (!wrapper) return;

                var self = this;
                this._themeObserver = new MutationObserver(function () {
                    var theme = detectTheme(self.$el);
                    if (theme.direction !== self.themeDirection || theme.isDark !== self.themeDark) {
                        self.themeDirection = theme.direction;
                        self.themeDark = theme.isDark;
                        self.refreshAllTags();
                    }
                });
                this._themeObserver.observe(wrapper, { attributes: true, attributeFilter: ['class'] });
            },

            refreshAllTags: function () {
                if (!this.tagify) return;
                var self = this;
                this.withSuppressedEvents(function () {
                    this.tagify.getTagElms().forEach(function (tagElm) {
                        var data = this.tagify.tagData(tagElm);
                        if (data) {
                            data.style = computeTagStyle(data.color || self.defaultColor, self.themeDirection, self.themeDark);
                            this.tagify.replaceTag(tagElm, data);
                        }
                    }.bind(this));
                });
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

            withSuppressedEvents: function (callback) {
                this.suppressEvents = true;

                try {
                    callback.call(this);
                } finally {
                    this.suppressEvents = false;
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
                var self = this;
                return tags.map(function (tag) {
                    return normalizeTag(tag, self.defaultColor, self.themeDirection, self.themeDark);
                }).filter(function (tag) {
                    return tag.value !== '';
                });
            },

            initTagify: function () {
                var self = this;
                return new window.Tagify(this.tagInput, {
                    whitelist: [],
                    transformTag: function (tagData) {
                        var tag = normalizeTag(tagData, self.defaultColor, self.themeDirection, self.themeDark);
                        tagData.color = tag.color;
                        tagData.style = tag.style;
                    },
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
                var normalized = normalizeTag(tag, this.defaultColor, this.themeDirection, this.themeDark);
                var index = findTagIndex(this.whitelist, normalized);

                if (index === -1) {
                    this.whitelist.push(normalized);
                } else {
                    this.whitelist[index] = Object.assign({}, this.whitelist[index], normalized);
                }

                this.syncTagifyWhitelist();
            },

            removeWhitelistTag: function (tag) {
                var normalized = normalizeTag(tag, this.defaultColor, this.themeDirection, this.themeDark);

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
                    style: computeTagStyle(color, this.themeDirection, this.themeDark),
                });

                this.callWire('changeColorTag', [tagData.value, color], function () {
                    this.withSuppressedEvents(function () {
                        this.tagify.replaceTag(this.activeTag.tag, updatedTag);
                    });

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
                    this.withSuppressedEvents(function () {
                        this.tagify.removeTags(tagData.value, true);
                    });

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
                var oldTag = normalizeTag(originalData, this.defaultColor, this.themeDirection, this.themeDark);
                var updatedTag = normalizeTag(data, this.defaultColor, this.themeDirection, this.themeDark);

                this.callWire('editTag', [data], function () {
                    this.upsertWhitelistTag(updatedTag);
                }, function () {
                    this.withSuppressedEvents(function () {
                        if (event.detail.tag) {
                            this.tagify.replaceTag(event.detail.tag, oldTag);

                            return;
                        }

                        this.tagify.removeTags(updatedTag.value, true);
                        this.tagify.addTags([oldTag]);
                    });
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
                    this.withSuppressedEvents(function () {
                        tags.forEach(function (tag) {
                            this.tagify.removeTags(tag.value, true);
                        }.bind(this));
                    });
                });
            },

            onRemoveTag: function (event) {
                if (this.suppressEvents) {
                    return;
                }

                var removedTag = normalizeTag(event.detail.data, this.defaultColor, this.themeDirection, this.themeDark);

                this.callWire('removeTag', [event.detail.data], null, function () {
                    this.withSuppressedEvents(function () {
                        this.tagify.addTags([removedTag]);
                    });
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
