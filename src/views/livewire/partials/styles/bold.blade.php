<style>
    /* ── Bold Direction ── */

    .livewire-tagify--bold {
        font-family: 'Space Grotesk', ui-sans-serif, system-ui, -apple-system, sans-serif;
    }

    .livewire-tagify--bold .tagify {
        --tags-border-color: #cdd1da;
        border-radius: 14px;
        padding: var(--tagify-input-padding, 6px 8px);
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
    }

    .livewire-tagify--bold .tagify.tagify--focus {
        border-color: #5b4cdb;
        box-shadow: 0 0 0 4px rgba(91,76,219,0.14);
    }

    .livewire-tagify--bold .tagify__tag {
        --tag-pad: 4px 4px 4px 8px;
        --tag-text-color: #111115;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
        letter-spacing: -0.01em;
    }

    .livewire-tagify--bold .tagify__tag:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.14);
    }

    .livewire-tagify--bold .tagify__tag > div::before {
        display: none;
    }

    .livewire-tagify--bold .tagify__tag-text {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--tag-text-color);
    }

    .livewire-tagify--bold .tagify__tag__removeBtn {
        width: 18px;
        height: 18px;
    }

    .livewire-tagify--bold .tagify__input {
        color: #111115;
    }

    .livewire-tagify--bold .tagify__input::before {
        color: #616879;
    }

    .livewire-tagify--bold .tagify__dropdown {
        border-radius: 14px;
        border-color: #dde0e7;
        box-shadow: 0 16px 48px rgba(0,0,0,0.15);
        transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
    }

    .livewire-tagify--bold .tagify__dropdown__item {
        border-radius: 10px;
        padding: 12px 16px;
        color: #111115;
    }

    .livewire-tagify--bold [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        border-radius: 14px !important;
        border-color: #dde0e7 !important;
        box-shadow: 0 16px 48px rgba(0,0,0,0.15);
    }

    .livewire-tagify--bold .livewire-tagify__section-label {
        color: #616879;
    }

    /* Bold + shapes */
    .livewire-tagify--bold[data-tagify-shape="pill"] .tagify { --tag-border-radius: 100px; }
    .livewire-tagify--bold[data-tagify-shape="rounded"] .tagify { border-radius: 12px; --tag-border-radius: 10px; }
    .livewire-tagify--bold[data-tagify-shape="square"] .tagify { border-radius: 8px; --tag-border-radius: 5px; }

    /* Bold + dark */
    .livewire-tagify--bold.livewire-tagify--dark .tagify {
        background: #18191e;
        border-color: #3a3d47;
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify:hover {
        border-color: #4a5060;
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify.tagify--focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 0 4px rgba(108,92,231,0.2);
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__tag {
        --tag-text-color: #eaedf2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.35);
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__tag:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.45);
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__input {
        color: #eaedf2;
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__input::before {
        color: #7e8594;
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__dropdown {
        background: #1e1f25;
        border-color: #3a3d47;
        box-shadow: 0 16px 48px rgba(0,0,0,0.6);
    }

    .livewire-tagify--bold.livewire-tagify--dark .tagify__dropdown__item {
        color: #eaedf2;
    }

    .livewire-tagify--bold.livewire-tagify--dark [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        background: #1e1f25;
        border-color: #3a3d47 !important;
        box-shadow: 0 16px 48px rgba(0,0,0,0.6);
    }

    .livewire-tagify--bold.livewire-tagify--dark .livewire-tagify__section-label {
        color: #7e8594;
    }
</style>
