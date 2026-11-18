<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_messages_follow_indonesian_locale(): void
    {
        $this->withCookie('locale', 'id')->post('/register', [])
            ->assertSessionHasErrors('email');

        $this->assertStringNotContainsString('field is required', session('errors')->first('email'));
    }

    public function test_validation_messages_stay_english_in_english_locale(): void
    {
        $this->withCookie('locale', 'en')->post('/register', [])
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString('field is required', session('errors')->first('email'));
    }
}