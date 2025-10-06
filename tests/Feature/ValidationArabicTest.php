<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationArabicTest extends TestCase
{
    public function test_arabic_validation_messages_are_returned_when_locale_is_ar(): void
    {
        // Set locale to Arabic for the request
        app()->setLocale('ar');

        $response = $this->post('/register', [
            'name' => '', // required
            'email' => 'not-an-email', // invalid email
            'password' => 'short', // too short depending on rules
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);

        $errors = session('errors')->getBag('default')->getMessages();

        // Assert a few Arabic phrases appear (best-effort, depends on rules)
        $this->assertTrue(collect($errors['name'] ?? [])->join(' ') !== '', 'Expected name errors');
        $this->assertTrue(str_contains(collect($errors['email'] ?? [])->join(' '), 'البريد الإلكتروني') || str_contains(collect($errors['email'] ?? [])->join(' '), 'يجب أن يكون'), 'Expected Arabic email error');
    }
}
