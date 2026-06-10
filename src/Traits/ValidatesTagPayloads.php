<?php

namespace Codekinz\LivewireTagify\Traits;

trait ValidatesTagPayloads
{
    protected function validatedTagValues($tagArray): array
    {
        if (! is_array($tagArray)) {
            return [];
        }

        $values = [];

        foreach ($tagArray as $tag) {
            if (! is_array($tag) || ! isset($tag['value']) || ! $this->isValidTagValue($tag['value'])) {
                continue;
            }

            $values[] = trim($tag['value']);
        }

        return array_values(array_unique($values));
    }

    protected function isValidTagId($tagId): bool
    {
        return is_int($tagId) || (is_string($tagId) && ctype_digit($tagId));
    }

    protected function isValidTagValue($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        $value = trim($value);

        return $value !== '' && strlen($value) <= (int) config('livewire-tagify.max_tag_length', 255);
    }

    protected function isAllowedColor($color): bool
    {
        if (! is_string($color)) {
            return false;
        }

        return in_array($color, array_values($this->preparedConfiguration('colors', [])), true);
    }
}
