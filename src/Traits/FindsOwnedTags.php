<?php

namespace Codekinz\LivewireTagify\Traits;

use Spatie\Tags\Tag;

trait FindsOwnedTags
{
    protected function findOwnedTagById(int|string $tagId): ?Tag
    {
        if (! $this->isValidTagId($tagId)) {
            return null;
        }

        return $this->modelCollection
            ->tags()
            ->where('tags.id', (int) $tagId)
            ->where('type', $this->tagType)
            ->first();
    }

    protected function findOwnedTagByValue(string $tagValue): ?Tag
    {
        if (! $this->isValidTagValue($tagValue)) {
            return null;
        }

        return $this->modelCollection
            ->tags()
            ->where('type', $this->tagType)
            ->get()
            ->first(function (Tag $tag) use ($tagValue): bool {
                return $tag->name === trim($tagValue);
            });
    }
}
