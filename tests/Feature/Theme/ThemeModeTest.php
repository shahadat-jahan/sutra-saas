<?php

declare(strict_types=1);

namespace Tests\Feature\Theme;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ThemeModeTest extends TestCase
{
    public function test_toggle_switches_between_dark_and_light_in_session(): void
    {
        $this->from('/')->post(route('theme.toggle'))->assertRedirect('/');
        $this->assertSame('light', session('theme_mode'));

        $this->from('/')->post(route('theme.toggle'))->assertRedirect('/');
        $this->assertSame('dark', session('theme_mode'));
    }

    public function test_set_persists_a_valid_mode_in_session(): void
    {
        $this->from('/')
            ->post(route('theme.set'), ['mode' => 'light'])
            ->assertRedirect('/');

        $this->assertSame('light', session('theme_mode'));
    }

    public function test_set_rejects_invalid_mode(): void
    {
        $this->from('/')
            ->post(route('theme.set'), ['mode' => 'nope'])
            ->assertSessionHasErrors('mode')
            ->assertRedirect('/');
    }

    public function test_theme_mode_is_shared_to_inertia_props(): void
    {
        $this->withSession(['theme_mode' => 'light'])
            ->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Welcome')
                ->where('themeMode', 'light')
            );
    }
}

