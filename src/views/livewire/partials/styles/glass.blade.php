<style>
    /* ── Glass Direction ── */

    .livewire-tagify--glass {
        font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
    }

    .livewire-tagify--glass .tagify {
        background: rgba(255,255,255,0.7);
        border-color: rgba(0,0,0,0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: var(--tagify-input-padding, 6px 8px);
        transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
    }

    .livewire-tagify--glass .tagify.tagify--focus {
        border-color: rgba(59,130,246,0.35);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
    }

    .livewire-tagify--glass .tagify__tag {
        --tag-pad: 3px 4px 3px 6px;
        border-color: rgba(0,0,0,0.06);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 1px 4px rgba(0,0,0,0.05), inset 0 1px 0 rgba(255,255,255,0.7);
        transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
    }

    .livewire-tagify--glass .tagify__tag:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.8);
    }

    .livewire-tagify--glass .tagify__tag > div::before {
        box-shadow: 0 0 6px var(--tag-color, rgba(43,124,209,0.5));
    }

    .livewire-tagify--glass .tagify__dropdown {
        background: rgba(255,255,255,0.88);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-color: rgba(0,0,0,0.06);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
    }

    .livewire-tagify--glass .tagify__dropdown__item {
        border-radius: 10px;
    }

    .livewire-tagify--glass [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        background: rgba(255,255,255,0.88);
        border-color: rgba(0,0,0,0.06) !important;
        border-radius: 16px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    /* Glass + shapes */
    .livewire-tagify--glass[data-tagify-shape="rounded"] .tagify { border-radius: 10px; --tag-border-radius: 10px; }
    .livewire-tagify--glass[data-tagify-shape="square"] .tagify { border-radius: 6px; --tag-border-radius: 6px; }

    /* Glass + dark */
    .livewire-tagify--glass.livewire-tagify--dark .tagify {
        background: rgba(255,255,255,0.04);
        border-color: rgba(255,255,255,0.1);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify.tagify--focus {
        border-color: rgba(139,180,255,0.4);
        box-shadow: 0 0 0 3px rgba(139,180,255,0.1);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__tag {
        border-color: rgba(255,255,255,0.08);
        box-shadow: 0 1px 4px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__tag:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.08);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__input {
        color: rgba(255,255,255,0.9);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__input::before {
        color: rgba(255,255,255,0.45);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__dropdown {
        background: rgba(28,31,38,0.92);
        border-color: rgba(255,255,255,0.08);
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__dropdown__item {
        color: rgba(255,255,255,0.9);
    }

    .livewire-tagify--glass.livewire-tagify--dark .tagify__dropdown__item--active,
    .livewire-tagify--glass.livewire-tagify--dark .tagify__dropdown__item:hover {
        background: rgba(255,255,255,0.06) !important;
    }

    .livewire-tagify--glass.livewire-tagify--dark [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        background: rgba(28,31,38,0.92);
        border-color: rgba(255,255,255,0.08) !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    .livewire-tagify--glass.livewire-tagify--dark .livewire-tagify__section-label {
        color: rgba(255,255,255,0.45);
    }
</style>
