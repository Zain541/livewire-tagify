<?php

use Codekinz\LivewireTagify\Components\LivewireTagify as PackageLivewireTagify;
use Codekinz\LivewireTagify\Tests\Support\TestModel;
use Codekinz\LivewireTagify\Tests\Support\TestEmptyTagPolicy;
use Codekinz\LivewireTagify\Tests\Support\TestTagPolicy;
use Codekinz\LivewireTagify\Tests\Support\TestTagComponent;
use Codekinz\LivewireTagify\Tests\Support\TestUntaggableModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\ViewException;
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

it('can use the package component directly', function () {
    $this->model->attachTag('Direct Tag', 'firstType');

    Livewire::test(PackageLivewireTagify::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ])->assertSee('Direct Tag');
});

it('rejects model classes that are not eloquent models', function () {
    Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => stdClass::class,
        'tagType' => 'firstType',
    ]);
})->throws(ViewException::class);

it('rejects models that do not support tags', function () {
    $model = TestUntaggableModel::query()->create(['name' => 'Plain Item']);

    Livewire::test(TestTagComponent::class, [
        'modelId' => $model->id,
        'modelClass' => TestUntaggableModel::class,
        'tagType' => 'firstType',
    ]);
})->throws(ViewException::class);

it('rejects missing models', function () {
    Livewire::test(TestTagComponent::class, [
        'modelId' => 999,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);
})->throws(ViewException::class);

it('rejects unsafe tag types', function () {
    Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'first type',
    ]);
})->throws(ViewException::class);

it('creates a new tag from the component action', function () {
    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $tagifyPayload = [['value' => 'Awesome Feature']];

    $component->call('addNewTag', $tagifyPayload);

    expect($this->model->refresh()->tags)
        ->count()->toBe(1)
        ->first()->name->toBe('Awesome Feature');
});

it('removes a tag from the component action', function () {
    $this->model->attachTag('Tag To Remove', 'firstType');

    $tag = $this->model->refresh()->tags->first();

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('removeTag', ['value' => 'Tag To Remove', 'id' => $tag->id]);

    expect($this->model->refresh()->tags)->count()->toBe(0);
});

it('updates tag color from the component action', function () {
    $this->model->attachTag('Design', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $newColor = '#E8634A';
    $component->call('changeColorTag', 'Design', $newColor);

    $tag = Tag::findFromString('Design', 'firstType');
    expect($tag->color)->toBe('#E8634A');
});

it('permanently deletes a tag from the database', function () {
    $this->model->attachTag('Useless Tag', 'firstType');
    $tag = Tag::findFromString('Useless Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('deleteTag', $tag->id);
    expect(Tag::query()->where('id', $tag->id)->exists())->toBeFalse();
});

it('uses the tailwind frontend view by default', function () {
    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    expect($component->instance()->frontendLibrary())->toBe('tailwind')
        ->and($component->instance()->frontendView())->toBe('livewire-tagify::livewire.tailwind');

    $component->assertSeeHtml('data-livewire-tagify-dropdown="tailwind"');
});

it('uses the bootstrap frontend view when configured', function () {
    config()->set('livewire-tagify.frontend_library', 'bootstrap');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    expect($component->instance()->frontendLibrary())->toBe('bootstrap')
        ->and($component->instance()->frontendView())->toBe('livewire-tagify::livewire.bootstrap');

    $component->assertSeeHtml('data-livewire-tagify-dropdown="bootstrap"');
});

it('uses the framework neutral frontend view when configured', function () {
    config()->set('livewire-tagify.frontend_library', 'none');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    expect($component->instance()->frontendLibrary())->toBe('none')
        ->and($component->instance()->frontendView())->toBe('livewire-tagify::livewire.none');

    $component->assertSeeHtml('data-livewire-tagify-dropdown="none"')
        ->assertDontSeeHtml('data-livewire-tagify-dropdown="tailwind"')
        ->assertDontSeeHtml('data-livewire-tagify-dropdown="bootstrap"');
});

it('falls back to tailwind for an unsupported frontend library', function () {
    config()->set('livewire-tagify.frontend_library', 'foundation');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    expect($component->instance()->frontendLibrary())->toBe('tailwind')
        ->and($component->instance()->frontendView())->toBe('livewire-tagify::livewire.tailwind');

    $component->assertSeeHtml('data-livewire-tagify-dropdown="tailwind"');
});

it('does not create tags when create permission is disabled', function () {
    config()->set('livewire-tagify.permissions.create', false);

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('addNewTag', [['value' => 'Blocked Tag']]);

    expect($this->model->refresh()->tags)->count()->toBe(0);
});

it('does not create tags from invalid payloads', function () {
    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('addNewTag', [['value' => ''], ['name' => 'Missing Value'], ['value' => ['Nested']]]);

    expect($this->model->refresh()->tags)->count()->toBe(0);
});

it('does not expose tags when read permission is disabled', function () {
    $this->model->attachTag('Hidden Tag', 'firstType');

    config()->set('livewire-tagify.permissions.read', false);

    Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ])->assertDontSee('Hidden Tag');
});

it('does not detach tags when delete permission is disabled', function () {
    $this->model->attachTag('Keep Attached', 'firstType');

    config()->set('livewire-tagify.permissions.delete', false);

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('removeTag', ['value' => 'Keep Attached']);

    expect($this->model->refresh()->tags)->count()->toBe(1);
});

it('does not edit tags owned by another model', function () {
    $otherModel = TestModel::query()->create(['name' => 'Other Item']);
    $otherModel->attachTag('Other Tag', 'firstType');
    $otherTag = Tag::findFromString('Other Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('editTag', ['id' => $otherTag->id, 'value' => 'Changed Tag']);

    expect(Tag::findFromString('Other Tag', 'firstType'))->not->toBeNull()
        ->and(Tag::findFromString('Changed Tag', 'firstType'))->toBeNull();
});

it('does not delete tags owned by another model', function () {
    $otherModel = TestModel::query()->create(['name' => 'Other Item']);
    $otherModel->attachTag('Other Tag', 'firstType');
    $otherTag = Tag::findFromString('Other Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('deleteTag', $otherTag->id);

    expect(Tag::query()->where('id', $otherTag->id)->exists())->toBeTrue();
});

it('does not trust browser supplied tag type when changing color', function () {
    $this->model->attachTag('Design', 'firstType');
    Tag::findOrCreate('Design', 'secondType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('changeColorTag', 'Design', '#E8634A');

    expect(Tag::findFromString('Design', 'firstType')->color)->toBe('#E8634A')
        ->and(Tag::findFromString('Design', 'secondType')->color)->toBeNull();
});

it('does not change tag color to a color outside the configured list', function () {
    $this->model->attachTag('Design', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('changeColorTag', 'Design', '#ff0000');

    expect(Tag::findFromString('Design', 'firstType')->color)->toBeNull();
});

it('uses tag policies before mutating tags', function () {
    Gate::policy(Tag::class, TestTagPolicy::class);
    $this->model->attachTag('Original Tag', 'firstType');
    $tag = Tag::findFromString('Original Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('editTag', ['id' => $tag->id, 'value' => 'Blocked Rename']);

    expect(Tag::findFromString('Original Tag', 'firstType'))->not->toBeNull()
        ->and(Tag::findFromString('Blocked Rename', 'firstType'))->toBeNull();
});

it('allows mutations when a tag policy exists without the relevant method', function () {
    Gate::policy(Tag::class, TestEmptyTagPolicy::class);
    $this->model->attachTag('Original Tag', 'firstType');
    $tag = Tag::findFromString('Original Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('editTag', ['id' => $tag->id, 'value' => 'Allowed Rename']);

    expect(Tag::findFromString('Allowed Rename', 'firstType'))->not->toBeNull();
});

it('uses configured permission gates before mutating tags', function () {
    Gate::define('deny-tag-update', function ($user = null, $tag = null, $model = null, $payload = [], $tagType = null) {
        return false;
    });

    config()->set('livewire-tagify.permission_gates.update', 'deny-tag-update');

    $this->model->attachTag('Original Tag', 'firstType');
    $tag = Tag::findFromString('Original Tag', 'firstType');

    $component = Livewire::test(TestTagComponent::class, [
        'modelId' => $this->model->id,
        'modelClass' => TestModel::class,
        'tagType' => 'firstType',
    ]);

    $component->call('editTag', ['id' => $tag->id, 'value' => 'Blocked Rename']);

    expect(Tag::findFromString('Original Tag', 'firstType'))->not->toBeNull()
        ->and(Tag::findFromString('Blocked Rename', 'firstType'))->toBeNull();
});
