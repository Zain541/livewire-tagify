<?php

namespace Codekinz\LivewireTagify\Tests\Browser;

use Codekinz\LivewireTagify\Tests\Support\TestModel;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;
use Throwable;

class TagInteractionTest extends DuskTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Data Setup
        DB::table('test_models')->truncate();
        DB::table('tags')->truncate();
        DB::table('taggables')->truncate();

        $this->model = TestModel::query()->create(['name' => 'Browser Item']);
    }

    /** @test
     * @throws Throwable
     */
    public function it_creates_a_tag_when_user_types_and_presses_enter()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dusk-test')
                ->waitFor('.tagify', 5)
                ->click('.tagify__input')
                ->keys('.tagify__input', 'Super Tag', '{enter}')
                ->waitForText('Super Tag', 5)
                ->pause(1000);
        });

        $tagExists = DB::table('tags')
            ->where('name', 'LIKE', '%Super Tag%')
            ->exists();

        expect($tagExists)->toBeTrue();
    }
}
