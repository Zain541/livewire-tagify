<?php

namespace Codekinz\LivewireTagify\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Codekinz\LivewireTagify\Traits\HasTags;

class TestModel extends Model
{
    use HasTags;

    protected $guarded = [];
    public $timestamps = false;
}
