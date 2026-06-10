<!-- README.md -->

# Livewire Easy Tags

Livewire Easy Tags is a powerful and convenient package that enhances the Livewire experience by simplifying the process of working with tags and tag inputs. With this package, you can easily integrate tags functionality into your Livewire components, allowing users to add, edit, remove tags and add colors to tags effortlessly.

## Installation

To install Livewire Easy Tags, use Composer:

```bash
composer require codekinz/livewire-tagify
```

## Prerequisite
- PHP 8.1 or higher
- A Laravel version supported by Livewire 4
- Livewire 4.x
- Alpine Js 3.x or higher
- Tagify 3.x or higher
- Spatie Laravel Tags 4.x
- Tailwind, Bootstrap, or your own CSS


## Getting Started

### Setup

Laravel should auto-discover the service provider. If package discovery is disabled, add it manually:

```php
// config/app.php

'providers' => [
    // Other service providers
    Codekinz\LivewireTagify\LivewireTagifyServiceProvider::class,
],
```
Publish the config and migration files:
```bash
php artisan vendor:publish --tag=livewire-tagify
```

The published migration safely prepares the tag tables. If `tags` or `taggables` already exist, it leaves them alone. If the `tags.color` column already exists, it leaves that alone too.

Run the migration:
```bash
php artisan migrate
```

On rollback, this package only removes the `color` column. It does not drop the `tags` or `taggables` tables because those tables may already belong to Spatie Laravel Tags or your app.

### Add trait to Laravel Model
This package uses <a href="https://spatie.be/docs/laravel-tags/v4/introduction" target="_blank">Laravel Spatie Tags</a> as an underlying package. Add this package's `HasTags` trait to your model.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Codekinz\LivewireTagify\Traits\HasTags;

class YourModel extends Model
{
    use HasFactory, HasTags;
    ...
}
```
### Usage
Now we are good to go. We just need to call the package Livewire component in a blade file.
```blade
@livewire('livewire-tagify', [
    'modelClass' => App\Models\User::class,
    'modelId' => 2,
    'tagType' => 'flights',
])
```
Here is the explanation of parameters
- `modelClass` must be an Eloquent model class that uses `Codekinz\LivewireTagify\Traits\HasTags`
- `modelId` must be an existing model record ID
- `tagType` must be a non-empty string using only letters, numbers, dashes, underscores, or colons

`tagType` lets you separate tags for different areas, such as `travel`, `bookings`, or `flights`.

## Configurations
Configurations are available at `config/livewire-tagify.php`. You can change the configuration in this file globally or extend the package component when one screen needs custom settings.

### Frontend library

The package ships with Tailwind, Bootstrap, and framework-neutral views. Tailwind is the default.

```php
// config/livewire-tagify.php

'frontend_library' => 'tailwind', // tailwind, bootstrap, or none
```

Use Bootstrap markup instead:

```php
'frontend_library' => 'bootstrap',
```

Use framework-neutral markup if you want to write your own CSS:

```php
'frontend_library' => 'none',
```

The package only changes the rendered markup/classes. Your app still needs to load Tagify, this package's JavaScript, and Alpine. If you choose `tailwind` or `bootstrap`, your app must also load that frontend library.

Publish and load the package JavaScript before Alpine starts:

```bash
php artisan vendor:publish --tag=livewire-tagify-assets
```

```blade
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="{{ asset('vendor/livewire-tagify/livewire-tagify.js') }}"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

You can publish the package views if you need to customize the markup:

```bash
php artisan vendor:publish --tag=livewire-tagify-views
```

### Permissions

Tag actions pass through three permission layers.

Layer 1 is the operation toggle. Set an action to `false` to always block it:

```php
'permissions' => [
    'create' => true,
    'read' => true,
    'update' => true,
    'delete' => true,
    'change_color' => true,
],
```

You may also set an action to a gate name here. This is kept for convenience, but `permission_gates` is clearer:

```php
'permissions' => [
    'delete' => 'delete-tags',
],
```

Layer 2 is the configured Laravel gate. Put the gate name in `permission_gates`:

```php
'permission_gates' => [
    'delete' => 'delete-tags',
],
```

Then define that gate in your Laravel app, for example in `AuthServiceProvider`:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Spatie\Tags\Tag;

Gate::define('delete-tags', function ($user, Tag $tag, Model $model, array $payload, ?string $tagType): bool {
    return $user->can('update', $model);
});
```

Gate arguments are always:

```php
[$tagOrTagClass, $taggableModel, $payload, $tagType]
```

For `create` and `read`, the first argument is `Spatie\Tags\Tag::class`. For `update`, `delete`, and `change_color`, it is the actual `Tag` model.

Layer 3 is the `Spatie\Tags\Tag` policy fallback. If no gate is configured, the package checks your policy for `Spatie\Tags\Tag` when one exists:

```php
public function update(?User $user, Tag $tag, Model $model): bool
{
    return $user?->can('update', $model) ?? false;
}
```

Policy ability mapping:

```php
'create' => 'create'
'read' => 'viewAny'
'update' => 'update'
'delete' => 'delete'
'change_color' => 'update'
```

If no toggle blocks the action, no configured gate exists, and no matching policy method exists, the package allows the action after validation and ownership checks pass.

The package also checks ownership before editing, deleting, detaching, or changing color. Browser-sent tag IDs and tag types are not trusted.

If one screen needs custom settings, create your own Livewire component that extends the package component:

```php
<?php

namespace App\Http\Livewire;

use Codekinz\LivewireTagify\Components\LivewireTagify;

class Tags extends LivewireTagify
{
    protected function configurations(): array
    {
       return [
            'colors' => [
                'lightblue' => '#add8e6',
                'lightgreen' => '#90ee90',
                'pink' => '#ffc0cb',
            ],
            'default_color' => 'yellow'
        ];
    }
}

```
## FAQs
Q. How to add or change colors of tags?  
A. If you click on any tag you will see a dropdown containing the list of colors and a delete button. Choose any color and you will see the effect

Q. How to delete a tag permenantly?  
A. Just like previously, you need to click on the tag you will see a dropdown containing list of colors and a delete button. Click on the delete button to remove that tag permanently.

Q. How to edit a tag?  
A. Double click on a tag and edit it.

## Upgrade Notes

If you previously created a Livewire component only to extend `LivewireTagify` and implement `TagsContract`, you can now remove that wrapper and call `@livewire('livewire-tagify', [...])` directly. Keep a custom component only when you need to override `configurations()` or add your own Livewire behavior.

The package migration now safely prepares the tag tables and adds `tags.color` only when needed. Rolling back this package removes only `tags.color`; it does not drop `tags` or `taggables`.

## Main Contributor
- [Zain Farooq](https://www.linkedin.com/in/zain-farooq-b3a914147)

  
## License
Livewire Easy Tags is open-source software licensed under the MIT license and powered by [Codekinz](https://codekinz.com)
