<?php

namespace Codekinz\LivewireTagify\Traits;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

trait InteractsWithTags
{
    use AuthorizesTagOperations;
    use FindsOwnedTags;
    use ResolvesTagifyConfiguration;
    use ValidatesTagPayloads;

    public string $componentKey;

    /** @var class-string<Model> */
    public string $modelClass;

    public int|string $modelId;

    public Model $modelCollection;

    public ?string $tagType = null;

    public function mount(): void
    {
        $this->modelCollection = (new $this->modelClass())->findOrFail($this->modelId);
        $this->componentKey = (string) Str::uuid();
    }

    public function addNewTag(array $tagArray): bool
    {
        $tagValues = $this->validatedTagValues($tagArray);

        if ($tagValues === [] || ! $this->allowsTagAction('create', null, ['values' => $tagValues])) {
            return false;
        }

        $this->modelCollection->syncTagsWithType($tagValues, $this->tagType);

        return true;
    }

    public function changeColorTag(string $tag, string $color): bool
    {
        if (! $this->isAllowedColor($color)) {
            return false;
        }

        $record = $this->findOwnedTagByValue($tag);

        if (! $record || ! $this->allowsTagAction('change_color', $record, ['color' => $color])) {
            return false;
        }

        return $record->update(['color' => $color]);
    }

    public function deleteTag(int|string $tagId): bool
    {
        $record = $this->findOwnedTagById($tagId);

        if (! $record || ! $this->allowsTagAction('delete', $record)) {
            return false;
        }

        return (bool) $record->delete();
    }

    public function getModelTags(): Collection
    {
        if (! $this->allowsTagAction('read')) {
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
                    'color' => $tag->color === null ? '#2B7CD1' : $tag->color,
                ];
            });
    }

    public function removeTag(array $tagsArray): bool
    {
        if (! isset($tagsArray['value']) || ! $this->isValidTagValue($tagsArray['value'])) {
            return false;
        }

        $record = $this->findOwnedTagByValue($tagsArray['value']);

        if (! $record || ! $this->allowsTagAction('delete', $record)) {
            return false;
        }

        $this->modelCollection->detachTag(trim($tagsArray['value']), $this->tagType);

        return true;
    }

    public function editTag(array $tagPayload): bool
    {
        if (! isset($tagPayload['id'], $tagPayload['value']) || ! $this->isValidTagValue($tagPayload['value'])) {
            return false;
        }

        $record = $this->findOwnedTagById($tagPayload['id']);

        if (! $record || ! $this->allowsTagAction('update', $record, ['value' => $tagPayload['value']])) {
            return false;
        }

        $record->name = trim($tagPayload['value']);

        return $record->save();
    }

    public function prepareWhitelist(): array
    {
        if (! $this->allowsTagAction('read')) {
            return [];
        }

        return Tag::where('type', $this->tagType)
            ->get()
            ->map(function (Tag $tag): array {
                return [
                    'id' => $tag->id,
                    'value' => $tag->name,
                    'color' => $tag->color === null ? '#2B7CD1' : $tag->color,
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
