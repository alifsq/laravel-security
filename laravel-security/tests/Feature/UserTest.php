<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testAuth()
    {
        $this->seed(UserSeeder::class);
        $response = Auth::attempt([
            'email' => 'budi@mail.com',
            'password' => 'rahasia'
        ], true);
        self::assertTrue($response);

        $user = Auth::user();
        self::assertNotNull($user);
        self::assertEquals('budi@mail.com', $user->email);
    }

    public function testlogin()
    {
        $this->seed(UserSeeder::class);
        $this->get("users/login?email=budi@mail.com&password=rahasia")
            ->assertRedirect('users/current');

        $this->get("users/login?email=wrong&password=wrong")
            ->assertSeeText("Wrong Crisidentials");
    }

    public function testCurrent()
    {
        Auth::logout();
        $this->seed(UserSeeder::class);

        $this->get('/users/current')
            ->assertStatus(302)
            ->assertRedirect('login');

        $user = User::where('email', 'budi@mail.com')->first();
        $this->actingAs($user)->get('/users/current')->assertSeeText('Hello Budi');
    }

    public function testHash()
    {
        $password = "rahasia";
        $hash = Hash::make($password);
        self::assertTrue(Hash::check($password, $hash));
    }


    public function testGuard()
    {
        $this->seed(UserSeeder::class);
        $this->get('api/users/current', [
            'Accept' => 'application/json',
        ])->assertStatus(401);

        $this->get("api/users/current", [
            "Accept" => "application/json",
            "API-Key" => "secret"
        ])
            ->assertStatus(200)
            ->assertSeeText("Hello Budi");
    }



}
