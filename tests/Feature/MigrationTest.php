<?php

use Illuminate\Support\Facades\Schema;

function livewireTagifyTagTablesMigration(): object
{
    return require __DIR__.'/../../src/Database/Migrations/prepare_tag_tables.php';
}

it('creates the tag tables and color column when they are missing', function () {
    Schema::dropIfExists('taggables');
    Schema::dropIfExists('tags');

    livewireTagifyTagTablesMigration()->up();

    expect(Schema::hasTable('tags'))->toBeTrue()
        ->and(Schema::hasTable('taggables'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'color'))->toBeTrue();
});

it('adds the color column when only the tags table already exists', function () {
    Schema::dropIfExists('taggables');
    Schema::dropIfExists('tags');

    Schema::create('tags', function ($table): void {
        $table->id();
        $table->json('name');
        $table->json('slug');
        $table->string('type')->nullable();
        $table->integer('order_column')->nullable();
        $table->timestamps();
    });

    livewireTagifyTagTablesMigration()->up();

    expect(Schema::hasTable('taggables'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'color'))->toBeTrue();
});

it('does not fail when the tag tables and color column already exist', function () {
    livewireTagifyTagTablesMigration()->up();

    expect(Schema::hasTable('tags'))->toBeTrue()
        ->and(Schema::hasTable('taggables'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'color'))->toBeTrue();
});

it('drops only the color column on rollback', function () {
    livewireTagifyTagTablesMigration()->down();

    expect(Schema::hasTable('tags'))->toBeTrue()
        ->and(Schema::hasTable('taggables'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'color'))->toBeFalse();
});
