<style>
    /* ── Shapes ── */

    [data-tagify-shape="rounded"] .tagify {
        border-radius: 10px;
        --tag-border-radius: 8px;
    }

    [data-tagify-shape="square"] .tagify {
        border-radius: 6px;
        --tag-border-radius: 4px;
    }

    [data-tagify-shape="rounded"] .tagify__dropdown,
    [data-tagify-shape="rounded"] [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        border-radius: 10px !important;
    }

    [data-tagify-shape="square"] .tagify__dropdown,
    [data-tagify-shape="square"] [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        border-radius: 6px !important;
    }

    [data-tagify-shape="rounded"] .tagify__dropdown__item { border-radius: 6px; }
    [data-tagify-shape="square"] .tagify__dropdown__item { border-radius: 3px; }
</style>
