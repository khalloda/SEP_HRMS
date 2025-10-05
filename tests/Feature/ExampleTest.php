<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Basic smoke test for public entry points.
     */
    public function test_application_redirects_guests_to_login(): void
    {
        $this->get('/')->assertRedirect();

        $this->get('/login')->assertOk();
    }
}
