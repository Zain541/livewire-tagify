<style>
    /* ── Accessibility — Touch targets ── */

    @media (pointer: coarse) {
        .tagify__tag__removeBtn {
            width: 24px;
            height: 24px;
        }

        .tagify__tag__removeBtn::after {
            font-size: 13px;
            line-height: 24px;
        }

        .livewire-tagify__color-button {
            width: 44px;
            height: 44px;
        }

        .tagify__dropdown__item {
            min-height: 44px;
            display: flex;
            align-items: center;
        }
    }
</style>
