<?php

namespace Codekinz\LivewireTagify\Contracts;

use Illuminate\Support\Collection;

interface TagsContract
{
    public function prepareConfigurations(): array;


    public function addNewTag($tagArray): void;


    public function changeColorTag($tag, $tagType, $color): void;


    public function deleteTag($tagId): void;


    public function getModelTags(): Collection;


    public function removeTag($tagsArray): void;


    public function editTag(array $objectToBeArray): void;

    public function prepareWhitelist(): string;


    public function prepareTransformTag(): string;

}
