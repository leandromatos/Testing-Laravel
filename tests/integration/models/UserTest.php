<?php

// use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\User;

class UserTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions;

    public function testAUserCanBeCreated()
    {
        $user = User::create([
            'name'     => 'User',
            'email'    => 'user@domain.com',
            'password' => bcrypt(123),
        ]);

        $latestUser = User::latest()->first();

        $this->assertEquals($user->id, $latestUser->id);
        $this->assertEquals('User', $latestUser->name);
        $this->assertEquals('user@domain.com', $latestUser->email);

        $this->seeInDatabase('users', [
            'name'  => 'User',
            'email' => 'user@domain.com',
        ]);
    }
}
