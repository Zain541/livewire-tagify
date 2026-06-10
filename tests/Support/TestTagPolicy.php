<?php

namespace Codekinz\LivewireTagify\Tests\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Tags\Tag;

class TestTagPolicy
{
    public function update(?Authenticatable $user, Tag $tag, $model): bool
    {
        return false;
    }
}
