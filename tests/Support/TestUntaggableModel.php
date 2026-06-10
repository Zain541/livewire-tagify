<?php

namespace Codekinz\LivewireTagify\Tests\Support;

use Illuminate\Database\Eloquent\Model;

class TestUntaggableModel extends Model
{
    protected $table = 'test_untaggable_models';

    protected $guarded = [];

    public $timestamps = false;
}
