<?php

namespace Codekinz\LivewireTagify\Contracts;

use Illuminate\Support\Collection;

interface TagsContract
{
    public function prepareConfigurations(): array;

    public function addNewTag(array $tagArray): bool;

    public function changeColorTag(string $tag, string $color): bool;

    public function deleteTag(int|string $tagId): bool;

    public function getModelTags(): Collection;

    public function removeTag(array $tagsArray): bool;

    public function editTag(array $tagPayload): bool;

    public function prepareWhitelist(): array;
}
