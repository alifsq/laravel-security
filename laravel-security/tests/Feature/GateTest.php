<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class GateTest extends TestCase
{
    public function testGateUser()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $user = User::where('email', 'budi@mail.com')->first();
        Auth::login($user);

        $contact = Contact::where('email', 'test@mail.com')->first();
        self::assertTrue(Gate::allows('get-contact', $contact));
        self::assertTrue(Gate::allows('update-contact', $contact));
        self::assertTrue(Gate::allows('delete-contact', $contact));
    }


    public function testGateMethod()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::where('email', 'budi@mail.com')->first();

        Auth::login($user);
        $contact = Contact::where('email', 'test@mail.com')->first();
        self::assertTrue(Gate::any(['get-contact', 'update-contact', 'delete-contact'], $contact));
        self::assertFalse(Gate::none(['get-contact', 'delete-contact', 'update-contact'], $contact));
    }

    public function testGateUserNonLogin(){
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $user = User::where('email','budi@mail.com')->first();
        $gate = Gate::forUser($user);

        $contact = Contact::where('email','test@mail.com')->first();
        self::assertTrue($gate->any(['get-contact','update-contact','delete-contact'],$contact));
    }

    public function testGateResponse(){
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $user = User::where('email','budi@mail.com')->first();
        Auth::login($user);

        $response = Gate::inspect('create-contact');
        self::assertFalse($response->allowed());
        self::assertEquals('You are not admin',$response->message());
    }

}
