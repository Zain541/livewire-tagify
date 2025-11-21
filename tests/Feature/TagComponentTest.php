<?php

use Codekinz\LivewireTagify\Tests\Support\TestModel;
use Codekinz\LivewireTagify\Tests\Support\TestTagComponent;
use Livewire\Livewire;
use Spatie\Tags\Tag;

beforeEach(function () {
    $this->model = TestModel::query()->create(['name' => 'Test Item']);
});

it('can initialize and load existing tags', function () {
    $this->model->attachTag('Existing Tag', 'firstType');

    $this->model->refresh();

    Livewire::test(TestTagComponent::class, [
        'modelId' => 1,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ])->assertSee('Existing Tag');
});

it('creates a new tag when event is emitted', function () {
    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $tagifyPayload = [['value' => 'Awesome Feature']];

    $component->emit('addNewTagEvent', $tagifyPayload);

    expect($this->model->refresh()->tags)
        ->count()->toBe(1)
        ->first()->name->toBe('Awesome Feature');
});

it('removes a tag when event is emitted', function () {
    $this->model->attachTag('Tag To Remove', 'firstType');

    $tag = $this->model->refresh()->tags->first();

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->emit('removeTagEvent', ['value' => 'Tag To Remove', 'id' => $tag->id]);

    expect($this->model->refresh()->tags)->count()->toBe(0);
});

it('updates tag color when event is emitted', function () {
    $this->model->attachTag('Design', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $newColor = '#FF0000';
    $component->emit('changeColorTagEvent', 'Design', 'firstType', $newColor);

    $tag = Tag::findFromString('Design', 'firstType');
    expect($tag->color)->toBe('#FF0000');
});

it('permanently deletes a tag from the database', function () {
    $this->model->attachTag('Useless Tag', 'firstType');
    $tag = Tag::findFromString('Useless Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->emit('deleteTagEvent', $tag->id);
    expect(Tag::query()->where('id', $tag->id)->exists())->toBeFalse();
});
