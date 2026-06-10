<?php

namespace Codekinz\LivewireTagify\Contracts;

use Illuminate\Support\Collection;

interface TagsContract
{
    public function prepareConfigurations(): array;

    public function addNewTag(array $tagArray): void;

    public function changeColorTag(string $tag, string $color): void;

    /**
     * @param  int|string  $tagId
     */
    public function deleteTag($tagId): void;

    public function getModelTags(): Collection;

    public function removeTag(array $tagsArray): void;

    public function editTag(array $tagPayload): void;

    public function prepareWhitelist(): array;
}
