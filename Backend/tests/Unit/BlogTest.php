<?php
namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\BlogService;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** test function which check unique username blog
     * @return void
     */
    public function testUniqueBlog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $blogService = new BlogService();
        $this->assertFalse($blogService->uniqueBlog($blog->username));
        $blog->delete();
        $user->delete();
    }
    /** test function which  get True primary blog
     * @return void
     */
    public function testTruePrimaryBlog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, "is_primary" => true]);
        $blogService = new BlogService();
        $checkedBlog = $blogService->getPrimaryBlog($user);
        $this->assertEquals($checkedBlog->id, $blog->id);
        $blog->delete();
        $user->delete();
    }
}
