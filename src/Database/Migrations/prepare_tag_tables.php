<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table): void {
                $table->id();
                $table->json('name');
                $table->json('slug');
                $table->string('type')->nullable();
                $table->integer('order_column')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('taggables')) {
            Schema::create('taggables', function (Blueprint $table): void {
                $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
                $table->morphs('taggable');
                $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
            });
        }

        if (Schema::hasTable('tags') && ! Schema::hasColumn('tags', 'color')) {
            Schema::table('tags', function (Blueprint $table): void {
                $table->string('color', 50)->nullable()->after('type');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tags') || ! Schema::hasColumn('tags', 'color')) {
            return;
        }

        Schema::table('tags', function (Blueprint $table): void {
            $table->dropColumn('color');
        });
    }
};
