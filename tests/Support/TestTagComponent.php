<?php

namespace Codekinz\LivewireTagify\Tests\Support;

use Codekinz\LivewireTagify\Components\LivewireTagify;
use Codekinz\LivewireTagify\Contracts\TagsContract;
use Codekinz\LivewireTagify\Traits\InteractsWithTags;

class TestTagComponent extends LivewireTagify implements TagsContract
{
    use InteractsWithTags;
}
