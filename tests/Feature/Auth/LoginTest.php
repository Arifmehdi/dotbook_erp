<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function test_user_can_not_login_with_incorrect_credentials()
    {
        $user = User::factory()->create();
        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'Incorrect Password',
        ]);

        $response->assertRedirect('/login');
        // $response->assertSessionHasErrors('username');
        $this->assertTrue(\session()->hasOldInput('username'));
        $this->assertFalse(\session()->hasOldInput('password'));
        $this->assertGuest();

    }

    /** @test */
    public function test_logged_in_user_see_auth_homepage()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user);
    }
}
