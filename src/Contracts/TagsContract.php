<?php

namespace Codekinz\LivewireTagify\Contracts;

use Illuminate\Support\Collection;

interface TagsContract
{
    public function prepareConfigurations(): array;


    public function addNewTag(array $tagArray): void;


    public function changeColorTag($tag, $tagType, $color): void;


    public function deleteTag($tagId): void;


    public function getModelTags(): Collection;


    public function removeTag(array $tagsArray): void;


    public function editTag(array $tagPayload): void;

    public function prepareWhitelist(): array;

}
