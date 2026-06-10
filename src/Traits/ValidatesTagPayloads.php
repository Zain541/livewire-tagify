<?php

namespace Codekinz\LivewireTagify\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ValidatesTagPayloads
{
    protected function validatedTagValues(array $tagArray): array
    {
        return collect($tagArray)
            ->map(fn (mixed $tag): mixed => $this->tagValueFromPayload($tag))
            ->filter(fn (mixed $value): bool => $this->isValidTagValue($value))
            ->unique()
            ->values()
            ->all();
    }

    protected function tagValueFromPayload(mixed $tag): mixed
    {
        if (! is_array($tag) || ! array_key_exists('value', $tag)) {
            return null;
        }

        return is_string($tag['value']) ? trim($tag['value']) : $tag['value'];
    }

    protected function isValidTagId(int|string $tagId): bool
    {
        return Validator::make(
            ['id' => $tagId],
            ['id' => ['required', 'integer']]
        )->passes();
    }

    protected function isValidTagValue(mixed $value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        return Validator::make(
            ['value' => trim($value)],
            ['value' => ['required', 'string', 'max:'.(int) config('livewire-tagify.max_tag_length', 255)]]
        )->passes();
    }

    protected function isAllowedColor(string $color): bool
    {
        $allowed = array_values($this->preparedConfiguration('colors', []));
        $darkColors = array_values($this->preparedConfiguration('dark_colors', []));

        return Validator::make(
            ['color' => $color],
            ['color' => ['required', 'string', Rule::in(array_merge($allowed, $darkColors))]]
        )->passes();
    }
}
