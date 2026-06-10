<style>
    /* ── Base / Refined direction — light mode ── */

    .livewire-tagify {
        font-family: 'DM Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
    }

    .livewire-tagify__sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        white-space: nowrap;
        border: 0;
    }

    .tagify {
        --tags-border-color: #d8dce3;
        --tags-hover-border-color: #b0b5bf;
        --tags-focus-border-color: #3b7bee;
        --tag-text-color: #2a2d35;
        --tag-text-color--edit: #2a2d35;
        --tag-border-radius: 100px;
        --tag-hover: transparent;
        --tag-inset-shadow-size: 1.1em;
        --tag-remove-bg: rgba(229, 62, 62, 0.08);
        --tag-remove-btn-bg--hover: #e53e3e;
        --tag-remove-btn-color: rgba(0,0,0,0.35);
        --tag-remove-btn-color--hover: #1a1a1a;
        --tag-pad: 3px 4px 3px 6px;
        --tag-bg: rgba(43,124,209,0.1);
        --tag-border-color: rgba(43,124,209,0.18);
        --tag-color: #2B7CD1;

        display: flex;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
        border-radius: 12px;
        padding: var(--tagify-input-padding, 6px 8px);
        line-height: normal;
        border: 1.5px solid var(--tags-border-color);
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
        gap: 6px;
        font-family: inherit;
    }

    .tagify:hover { border-color: var(--tags-hover-border-color); }

    .tagify.tagify--focus {
        border-color: var(--tags-focus-border-color);
        box-shadow: 0 0 0 3px rgba(59,123,238,0.12);
    }

    .tagify__tag {
        margin: 0 !important;
        border-radius: var(--tag-border-radius);
        background: var(--tag-bg);
        border: 1px solid var(--tag-border-color);
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
        font-weight: 500;
        font-size: 13px;
        line-height: 1.3;
        padding: var(--tag-pad);
    }

    .tagify__tag:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .tagify__tag:focus {
        outline: 2px solid var(--tags-focus-border-color);
        outline-offset: 1px;
    }

    .tagify__tag > div {
        padding: 0 !important;
        margin: 0 !important;
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--tag-text-color);
    }

    .tagify__tag > div::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--tag-color, var(--tag-bg));
        flex-shrink: 0;
        box-shadow: none;
        position: static;
        opacity: 1;
        animation: none;
        top: auto; right: auto; bottom: auto; left: auto;
        pointer-events: none;
    }

    .tagify__tag-text {
        color: var(--tag-text-color);
        font-weight: 500;
        font-size: 13px;
    }

    .tagify__tag.tagify--noAnim > div::before { animation: none !important; }

    .tagify__tag__removeBtn {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-left: 4px;
        order: 10;
        color: var(--tag-remove-btn-color);
        background: transparent;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }

    .tagify__tag__removeBtn:hover {
        background: rgba(0,0,0,0.1);
        color: var(--tag-remove-btn-color--hover);
        transform: scale(1.15);
    }

    .tagify__tag__removeBtn::after {
        font-size: 11px;
        line-height: 16px;
    }

    .tagify__input {
        margin: 0 !important;
        padding: 0 4px !important;
        line-height: normal !important;
        font-size: 14px;
        font-family: inherit;
        color: #1a1d24;
    }

    .tagify__input::before {
        color: #6b7280;
        opacity: 0.6;
        font-weight: 400;
    }

    .tagify__dropdown {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        z-index: 100;
        margin-top: 6px !important;
        padding: 6px;
        animation: tagifyDropIn 0.2s ease-out;
    }

    @keyframes tagifyDropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .tagify__dropdown__wrapper {
        border: none !important;
        border-radius: 12px !important;
        padding: 0;
        background: transparent;
        box-shadow: none;
    }

    .tagify__dropdown__item {
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 2px;
        font-size: 13px;
        font-weight: 500;
        color: #1a1d24;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }

    .tagify__dropdown__item--active,
    .tagify__dropdown__item:hover {
        background: rgba(0,0,0,0.04) !important;
        color: #1a1d24 !important;
    }

    [data-livewire-tagify-dropdown] .tagify__dropdown__wrapper {
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        padding: 6px;
        background: #ffffff;
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }

    .livewire-tagify__color-button {
        border: 2px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        display: inline-block;
        height: 32px;
        width: 32px;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
        padding: 0;
    }

    .livewire-tagify__color-button:hover {
        transform: scale(1.12);
        border-color: #1a1d24;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .livewire-tagify__color-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    [data-livewire-tagify-dropdown] .tagify__dropdown__item:first-child {
        color: #6b7280;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }

    [data-livewire-tagify-dropdown] .tagify__dropdown__item:first-child:hover {
        background: rgba(229, 62, 62, 0.06) !important;
        color: #e53e3e !important;
    }

    .livewire-tagify__section-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .tagify__tag.tagify__tag--error {
        border-color: #e53e3e;
        background: rgba(229,62,62,0.06);
    }

    .tagify__tag.tagify__tag--editable > div {
        outline: 2px solid #3b7bee;
        outline-offset: 1px;
        border-radius: var(--tag-border-radius);
    }
</style>
