<?php

namespace Codekinz\LivewireTagify\Traits;

use Spatie\Tags\Tag;
use Illuminate\Support\Collection;

trait InteractsWithTags
{
    use AuthorizesTagOperations;

    public $componentKey;
    public $modelClass;
    public $modelId;
    public $modelCollection;

    public $tagType;

    protected ?array $preparedConfigurations = null;

    public function mount()
    {
        $this->modelCollection = (new $this->modelClass())->findOrFail($this->modelId);
        $this->componentKey = rand(1, 1000000) . microtime(true);
    }

    protected function getListeners()
    {
        return [
            'addNewTagEvent' => 'addNewTag',
            'removeTagEvent' => 'removeTag',
            'editTagEvent' => 'editTag',
            'deleteTagEvent' => 'deleteTag',
            'changeColorTagEvent' => 'changeColorTag'
        ];
    }

    public function prepareConfigurations(): array
    {
        return $this->preparedConfigurations ??= $this->resolveConfigurations();
    }

    protected function resolveConfigurations(): array
    {
        $defaults = [
            'colors' => config('livewire-tagify.colors', []),
            'default_color' => config('livewire-tagify.default_color', 'lightgray'),
            'frontend_library' => config('livewire-tagify.frontend_library', 'tailwind'),
        ];

        return array_merge($defaults, $this->validConfigurationOverrides($this->configurations()));
    }

    protected function validConfigurationOverrides(array $configurations): array
    {
        $overrides = [];

        if (! empty($configurations['colors']) && is_array($configurations['colors'])) {
            $overrides['colors'] = $configurations['colors'];
        }

        if (! empty($configurations['default_color'])) {
            $overrides['default_color'] = $configurations['default_color'];
        }

        if (! empty($configurations['frontend_library'])) {
            $overrides['frontend_library'] = $configurations['frontend_library'];
        }

        return $overrides;
    }

    public function frontendLibrary(): string
    {
        $frontendLibrary = $this->preparedConfiguration('frontend_library', 'tailwind');

        return in_array($frontendLibrary, ['tailwind', 'bootstrap', 'none'], true) ? $frontendLibrary : 'tailwind';
    }

    public function frontendView(): string
    {
        $frontendLibrary = $this->frontendLibrary();
        $frontendViews = config('livewire-tagify.frontend_views', []);

        return $frontendViews[$frontendLibrary] ?? 'livewire-tagify::livewire.tailwind';
    }


    public function addNewTag($tagArray): void
    {
        $tagValues = $this->validatedTagValues($tagArray);

        if ($tagValues === [] || ! $this->canPerformTagOperation('create', null, ['values' => $tagValues])) {
            return;
        }

        $this->modelCollection->syncTagsWithType($tagValues, $this->tagType);
    }

    public function changeColorTag($tag, $tagType, $color): void
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

    public function deleteTag($tagId): void
    {
        $record = $this->findOwnedTagById($tagId);

        if (! $record || ! $this->canPerformTagOperation('delete', $record)) {
            return;
        }

        $record->delete();
    }

    /**
     * Get model tags of the first type.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getModelTags(): Collection
    {
        if (! $this->canPerformTagOperation('read')) {
            return collect();
        }

        // Retrieve the tags with the specified type
        $tags = $this->modelCollection->tags()->where('type', $this->tagType)->get();

        // Map the tags to the desired format
        $mappedTags = $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'value' => $tag->name,
                'type' => $tag->type,
                'color' => $tag->color == null ? 'lightgray' : $tag->color,
            ];
        });

        // Return the mapped tags
        return $mappedTags;
    }

    public function removeTag($tagsArray): void
    {
        if (! is_array($tagsArray) || ! isset($tagsArray['value']) || ! $this->isValidTagValue($tagsArray['value'])) {
            return;
        }

        $record = $this->findOwnedTagByValue($tagsArray['value']);

        if (! $record || ! $this->canPerformTagOperation('delete', $record)) {
            return;
        }

        $this->modelCollection->detachTag($tagsArray['value'], $this->tagType);
    }


    /**
     * Edit a tag.
     *
     * @param  array  $objectToBeArray The array containing the tag data.
     * @return void
     */
    public function editTag(array $objectToBeArray): void
    {
        if (! isset($objectToBeArray['id'], $objectToBeArray['value']) || ! $this->isValidTagValue($objectToBeArray['value'])) {
            return;
        }

        $record = $this->findOwnedTagById($objectToBeArray['id']);

        if (! $record || ! $this->canPerformTagOperation('update', $record, ['value' => $objectToBeArray['value']])) {
            return;
        }

        // Update the tag
        $record->name = $objectToBeArray['value'];
        $record->save();
    }

    public function prepareWhitelist(): string
    {
        if (! $this->canPerformTagOperation('read')) {
            return '';
        }

        // Retrieve the tags with the specified type
        $tags = Tag::where('type', $this->tagType)->get();

        $mappedTags = $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'value' => $tag->name,
                'type' => $tag->type,
                'color' => $tag->color
            ];
        });

        $data = json_decode($mappedTags, true);
        $convertedArray = array_map(function ($item) {
            $tagColor = $item['color'] == null ? 'lightgray' : $item['color'];
            return "{'id': {$item['id']}, 'value': '{$item['value']}', 'color': '{$tagColor}'}";
        }, $data);
        $result = implode(',', $convertedArray);
        return $result;
    }

    public function prepareTransformTag(): string
    {
        $tags = Tag::get();
        $whitelist = '';
        $whitelistArray = [];
        foreach ($tags as $tag) {
            $whitelistArray[] = $tag->name;
        }
        $whitelist .= "{'value': 'working', color: 'pink', style: '--tag-bg:pink'},{'value': 'great', 'color': 'yellow'}";
        return $whitelist;
    }

    protected function configurations(): array
    {
        return [];
    }

    protected function findOwnedTagById($tagId): ?Tag
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

    protected function findOwnedTagByValue($tagValue): ?Tag
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

    protected function preparedConfiguration(string $key, $default = null)
    {
        return $this->prepareConfigurations()[$key] ?? $default;
    }

    public function render()
    {
        return view($this->frontendView());
    }
}
