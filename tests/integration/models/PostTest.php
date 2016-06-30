<?php

use App\Post;
use App\User;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        // Given:
        // I have a post
        $this->post = factory(Post::class)->create();
        // and a user and that user is logged in
        $this->signIn();
    }

    /** @test */
    public function a_user_can_like_a_post()
    {
        // Given
        // check out set up

        // Wheen
        // they like a post
        $this->post->like();

        // Then
        // we should see evidence in the database
        $this->seeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => get_class($this->post),
        ]);
        // and the post shoud be liked
        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unlinke_a_post()
    {
        // Given
        // check out set up

        // Wheen
        // they like a post
        $this->post->like();
        // they unlike a post
        $this->post->unlike();

        // Then
        // we should not see evidence in the database
        $this->notSeeInDatabase('likes', [
            'user_id'       => $this->user->id,
            'likeable_id'   => $this->post->id,
            'likeable_type' => get_class($this->post),
        ]);
        // and the post not shoud be liked
        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_unlike()
    {
        // Given
        // check out set up

        // Wheen
        // they toggle like/unlike a post
        $this->post->toggle();

        // Then
        // and the post shoud be liked
        $this->assertTrue($this->post->isLiked());

        // Wheen
        // they toggle like/unlike a post
        $this->post->toggle();

        // Then
        // and the post not shoud be liked
        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        // Given
        // check out set up

        // Wheen
        // they toggle like/unlike a post
        $this->post->toggle();

        // Then
        // and the likes count
        $this->assertEquals(1, $this->post->likesCount);
    }
}
