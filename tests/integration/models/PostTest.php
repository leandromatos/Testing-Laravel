<?php

use App\Post;
use App\User;
// use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_like_a_post()
    {
        // Given:
        // I have a post
        $post = factory(Post::class)->create();
        // and a user
        $user = factory(User::class)->create();
        // and that user is logged in
        $this->actingAs($user);

        // Wheen
        // they like a post
        $post->like();

        // Then
        // we should see evidence in the database
        $this->seeInDatabase('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $post->id,
            'likeable_type' => get_class($post),
        ]);
        // and the post shoud be liked
        $this->assertTrue($post->isLiked());
    }

    /** @test */
    public function a_user_can_unlinke_a_post()
    {
        // Given:
        // I have a post
        $post = factory(Post::class)->create();
        // and a user
        $user = factory(User::class)->create();
        // and that user is logged in
        $this->actingAs($user);

        // Wheen
        // they like a post
        $post->like();
        // they unlike a post
        $post->unlike();

        // Then
        // we should not see evidence in the database
        $this->notSeeInDatabase('likes', [
            'user_id'       => $user->id,
            'likeable_id'   => $post->id,
            'likeable_type' => get_class($post),
        ]);
        // and the post not shoud be liked
        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_unlike()
    {
        // Given:
        // I have a post
        $post = factory(Post::class)->create();
        // and a user
        $user = factory(User::class)->create();
        // and that user is logged in
        $this->actingAs($user);

        // Wheen
        // they toggle like/unlike a post
        $post->toggle();

        // Then
        // and the post shoud be liked
        $this->assertTrue($post->isLiked());

        // Wheen
        // they toggle like/unlike a post
        $post->toggle();

        // Then
        // and the post not shoud be liked
        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_post_knows_how_many_likes_it_has()
    {
        // Given:
        // I have a post
        $post = factory(Post::class)->create();
        // and a user
        $user = factory(User::class)->create();
        // and that user is logged in
        $this->actingAs($user);

        // Wheen
        // they toggle like/unlike a post
        $post->toggle();

        // Then
        // and the likes count
        $this->assertEquals(1, $post->likesCount);
    }
}
