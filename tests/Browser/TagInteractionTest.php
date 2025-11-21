<?php

namespace Codekinz\LivewireTagify\Tests\Browser;

use Codekinz\LivewireTagify\Tests\Support\TestModel;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;
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

    /** @test */
    public function it_selects_an_existing_tag_from_the_dropdown()
    {
        Tag::query()->create(['name' => 'Available Tag', 'type' => 'firstType']);

        $this->browse(function (Browser $browser) {
            $browser->visit('/dusk-test')
                ->waitFor('.tagify', 5)
                ->click('.tagify__input')
                ->waitForText('Available Tag', 5)
                ->keys('.tagify__input', '{arrow_down}', '{enter}')
                ->waitForText('Available Tag', 5);
        });

        $this->assertEquals(1, $this->model->refresh()->tags->count());
    }

    /** @test */
    public function it_edits_a_tag_by_double_clicking_and_pressing_enter()
    {
        $this->model->attachTag('Old Name', 'firstType');

        $this->browse(function (Browser $browser) {
            $browser->visit('/dusk-test')
                ->waitFor('.tagify', 5)
                ->assertSee('Old Name')
                ->script("
                    var el = document.querySelector('.tagify__tag-text');
                    el.dispatchEvent(new MouseEvent('dblclick', {bubbles: true, cancelable: true, view: window}));
                    
                    setTimeout(() => {
                        el.textContent = ''; // 2. CLEAR TEXT INSTANTLY
                        el.focus();          // 3. FORCE FOCUS
                    }, 100);
                ");

            $browser->waitFor('.tagify__tag-text[contenteditable="true"]', 2)
                ->keys('.tagify__tag-text', 'New Name', '{enter}')
                ->waitForText('New Name', 5)
                ->assertDontSee('Old Name');
        });

        $this->assertDatabaseHas('tags', [
            'name' => json_encode(['en' => 'New Name']), // Spatie uses JSON
        ]);
    }

    /** @test */
    public function it_deletes_a_tag_using_the_custom_dropdown()
    {
        $this->model->attachTag('Delete Me', 'firstType');

        $this->browse(function (Browser $browser) {
            $browser->visit('/dusk-test')
                ->waitFor('.tagify', 5)
                ->assertSee('Delete Me')
                ->click('.tagify__tag')
                ->waitFor('.tagify__dropdown', 2)
                ->script("
                    const dropdown = document.querySelector('.tagify__dropdown');
                    if (dropdown) {
                        const buttons = Array.from(dropdown.querySelectorAll('button'));
                        const deleteBtn = buttons.find(b => b.textContent.includes('Delete Tag'));
                        if (deleteBtn) deleteBtn.click();
                    }
                ");

            $browser->waitUntilMissing('.tagify__tag', 5);
        });

        $tagExists = Tag::query()
            ->where('name', 'LIKE', '%Delete Me%')
            ->exists();

        $this->assertFalse($tagExists, 'The tag was not deleted from the database.');
    }

    /** @test */
    public function it_removes_a_tag_clicking_the_cross_button()
    {
        $this->model->attachTag('Remove Me', 'firstType');

        $this->browse(function (Browser $browser) {
            $browser->visit('/dusk-test')
                ->waitFor('.tagify', 5)
                ->mouseover('.tagify__tag')
                ->click('.tagify__tag__removeBtn')
                ->waitUntilMissing('.tagify__tag', 5);
        });

        $this->assertEquals(0, $this->model->refresh()->tags->count());
    }
}
