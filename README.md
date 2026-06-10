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
Publish the migration and config files
```bash
php artisan vendor:publish --tag=livewire-tagify
```
Run the migration
```bash
php artisan migrate
```

### Set up the Livewire component
In order to use Livewire tagify, you will first need to create a Livewire component
```bash
php artisan make:livewire Tags
```
In Livewire Tags component, instead of extending the Livewire class you will need to extend the `LivewireTagify`. You Tags component should look like this

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Codekinz\LivewireTagify\Components\LivewireTagify;
use Codekinz\LivewireTagify\Contracts\TagsContract;
use Codekinz\LivewireTagify\Traits\InteractsWithTags;

class Tags extends LivewireTagify implements TagsContract
{
    use InteractsWithTags;
}

```
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
Now we are good to go. We just need to call our Livewire component in a blade file.
```blade
 @livewire('tags',
        [
            'modelClass' => App\Models\User::class,
            'modelId' => 2,
            'tagType' => 'flights'
        ])
```
Here is the explanation of parameters
- `modelClass` is the class of the model that you want to associate with the tag
- `modelId` is the record identifier i.e primary key value
- `tagType` allows you to set up tags for multiple modules. For instance, you need to use tags for multiple modules like travel, bookings and flights then the `tagType` parameter will serve the purpose.

## Configurations
Configurations are available at `config/livewire-tagify.php`. You can change the configuration in this file globally or you can use this function in your `Tags` component if you want to have multiple tags component

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

Tag operations are enabled by default. You can disable any operation in `config/livewire-tagify.php`:

```php
'permissions' => [
    'create' => true,
    'read' => true,
    'update' => true,
    'delete' => true,
    'change_color' => true,
],
```

You can also connect operations to Laravel gates:

```php
'permission_gates' => [
    'delete' => 'delete-tags',
],
```

If no gate is configured, the package checks your Laravel policy for `Spatie\Tags\Tag` when one exists. If no gate or policy exists, the operation is allowed after package validation and ownership checks pass.

```php
public function update(?User $user, Tag $tag, Model $model): bool
{
    return $user?->can('update', $model) ?? false;
}
```

The package also checks ownership before editing, deleting, detaching, or changing color. Browser-sent tag IDs and tag types are not trusted.

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Codekinz\LivewireTagify\Components\LivewireTagify;
use Codekinz\LivewireTagify\Contracts\TagsContract;
use Codekinz\LivewireTagify\Traits\InteractsWithTags;

class Tags extends LivewireTagify implements TagsContract
{
    use InteractsWithTags;

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

## Main Contributor
- [Zain Farooq](https://www.linkedin.com/in/zain-farooq-b3a914147)

  
## License
Livewire Easy Tags is open-source software licensed under the MIT license and powered by [Codekinz](https://codekinz.com)
