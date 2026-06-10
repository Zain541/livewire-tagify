<style>
    /* ── Dark Mode ── */

    .livewire-tagify--dark .tagify {
        --tags-border-color: #333842;
        --tags-hover-border-color: #4a5060;
        --tags-focus-border-color: #5b8af0;
        --tag-text-color: #e2e5ea;
        --tag-text-color--edit: #e2e5ea;
        --tag-remove-btn-color: rgba(255,255,255,0.4);
        --tag-remove-btn-color--hover: #fff;
        --tag-bg: rgba(43,124,209,0.18);
        --tag-border-color: rgba(43,124,209,0.25);
        --tag-color: #2B7CD1;

        background: #1c1f26;
        border-color: #333842;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .livewire-tagify--dark .tagify:hover {
        border-color: #4a5060;
    }

    .livewire-tagify--dark .tagify.tagify--focus {
        border-color: #5b8af0;
        box-shadow: 0 0 0 3px rgba(91,138,240,0.15);
    }

    .livewire-tagify--dark .tagify__tag {
        box-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .livewire-tagify--dark .tagify__tag:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        transform: translateY(-1px);
    }

    .livewire-tagify--dark .tagify__tag__removeBtn:hover {
        background: rgba(255,255,255,0.15);
    }

    .livewire-tagify--dark .tagify__input {
        color: #e2e5ea;
    }

    .livewire-tagify--dark .tagify__input::before {
        color: #8a919e;
    }

    .livewire-tagify--dark .tagify__dropdown {
        background: #1c1f26;
        border-color: #333842;
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
    }

    .livewire-tagify--dark .tagify__dropdown__item {
        color: #e2e5ea;
    }

    .livewire-tagify--dark .tagify__dropdown__item--active,
    .livewire-tagify--dark .tagify__dropdown__item:hover {
        background: rgba(255,255,255,0.06) !important;
        color: #e2e5ea !important;
    }

    .livewire-tagify--dark [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        border-color: #333842 !important;
        background: #1c1f26;
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
    }

    .livewire-tagify--dark .livewire-tagify__color-button:hover {
        border-color: #fff;
    }

    .livewire-tagify--dark .livewire-tagify__section-label {
        color: #8a919e;
    }

    .livewire-tagify--dark [data-livewire-tagify-dropdown] .tagify__dropdown__item:first-child {
        color: #8a919e;
    }

    .livewire-tagify--dark .tagify__tag.tagify__tag--editable > div {
        outline-color: #5b8af0;
    }
</style>
