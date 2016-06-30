<?php

// use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\User;

class UserTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions, MailTracking;

    /** @test */
    public function a_user_can_be_created()
    {
        $user = User::create([
            'name'     => 'User',
            'email'    => 'user@domain.com',
            'password' => bcrypt(123),
        ]);

        Mail::raw('Helo World', function ($message) {
            $message->to('user@domain.com');
            $message->from('bar@foo.com');
            $message->subject('User created!');
        });

        $latestUser = User::latest()->first();

        $this->assertEquals($user->id, $latestUser->id);
        $this->assertEquals('User', $latestUser->name);
        $this->assertEquals('user@domain.com', $latestUser->email);

        $this->seeEmailEquals('Helo World')
            ->seeEmailSubjectEquals('User created!');

        $this->seeInDatabase('users', [
            'name'  => 'User',
            'email' => 'user@domain.com',
        ]);
    }
}
