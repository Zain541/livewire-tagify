<?php

namespace Codekinz\LivewireTagify\Traits;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

trait InteractsWithTags
{
    use AuthorizesTagOperations;
    use FindsOwnedTags;
    use ResolvesTagifyConfiguration;
    use ValidatesTagPayloads;

    /** @var string */
    public $componentKey;

    /** @var class-string */
    public $modelClass;

    /** @var int|string */
    public $modelId;

    /** @var \Illuminate\Database\Eloquent\Model */
    public $modelCollection;

    /** @var string|null */
    public $tagType;

    public function mount(): void
    {
        $this->modelCollection = (new $this->modelClass())->findOrFail($this->modelId);
        $this->componentKey = (string) Str::uuid();
    }

    protected function getListeners(): array
    {
        return [
            'addNewTagEvent' => 'addNewTag',
            'removeTagEvent' => 'removeTag',
            'editTagEvent' => 'editTag',
            'deleteTagEvent' => 'deleteTag',
            'changeColorTagEvent' => 'changeColorTag',
        ];
    }

    public function addNewTag(array $tagArray): void
    {
        $tagValues = $this->validatedTagValues($tagArray);

        if ($tagValues === [] || ! $this->canPerformTagOperation('create', null, ['values' => $tagValues])) {
            return;
        }

        $this->modelCollection->syncTagsWithType($tagValues, $this->tagType);
    }

    public function changeColorTag(string $tag, string $tagType, string $color): void
    {
        if (! $this->isAllowedColor($color)) {
            return;
        }

        $record = $this->findOwnedTagByValue($tag);

        if (! $record || ! $this->canPerformTagOperation('change_color', $record, ['color' => $color])) {
            return;
        }

        $record->update(['color' => $color]);
    }

    /**
     * @param  int|string  $tagId
     */
    public function deleteTag($tagId): void
    {
        $record = $this->findOwnedTagById($tagId);

        if (! $record || ! $this->canPerformTagOperation('delete', $record)) {
            return;
        }

        $record->delete();
    }

    public function getModelTags(): Collection
    {
        if (! $this->canPerformTagOperation('read')) {
            return collect();
        }

        return $this->modelCollection
            ->tags()
            ->where('type', $this->tagType)
            ->get()
            ->map(function (Tag $tag): array {
                return [
                    'id' => $tag->id,
                    'value' => $tag->name,
                    'type' => $tag->type,
                    'color' => $tag->color === null ? 'lightgray' : $tag->color,
                ];
            });
    }

    public function removeTag(array $tagsArray): void
    {
        if (! isset($tagsArray['value']) || ! $this->isValidTagValue($tagsArray['value'])) {
            return;
        }

        $record = $this->findOwnedTagByValue($tagsArray['value']);

        if (! $record || ! $this->canPerformTagOperation('delete', $record)) {
            return;
        }

        $this->modelCollection->detachTag(trim($tagsArray['value']), $this->tagType);
    }

    public function editTag(array $tagPayload): void
    {
        if (! isset($tagPayload['id'], $tagPayload['value']) || ! $this->isValidTagValue($tagPayload['value'])) {
            return;
        }

        $record = $this->findOwnedTagById($tagPayload['id']);

        if (! $record || ! $this->canPerformTagOperation('update', $record, ['value' => $tagPayload['value']])) {
            return;
        }

        $record->name = trim($tagPayload['value']);
        $record->save();
    }

    public function prepareWhitelist(): array
    {
        if (! $this->canPerformTagOperation('read')) {
            return [];
        }

        return Tag::where('type', $this->tagType)
            ->get()
            ->map(function (Tag $tag): array {
                return [
                    'id' => $tag->id,
                    'value' => $tag->name,
                    'color' => $tag->color === null ? 'lightgray' : $tag->color,
                ];
            })
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view($this->frontendView());
    }
}
