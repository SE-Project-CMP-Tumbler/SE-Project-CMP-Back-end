<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FollowBlogTest extends TestCase
{
    public function testTrueFollowBlog()
    {
        $userFollower = User::factory()->create();
        $userFollowed = User::factory()->create();
        $blogFollower = Blog::factory()->create(['user_id' => $userFollower->id]);
        $blogFollowed =  Blog::factory()->create(['user_id' => $userFollowed->id]);
        
    }
}
