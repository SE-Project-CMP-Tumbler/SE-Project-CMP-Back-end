<?php

namespace Tests\Unit;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\User;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SearchService;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /** test function which  search about word in posts or tags of posts
     * @return void
     */
    public function testsSearch()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(["user_id" => $user->id]);
        $post = Post::factory(10)->create(["blog_id" => $blog->id , "body" =>  "<div><h1>What's Artificial @intellegence? </h1> <p> ok </p></div>"]);
        $searchService = new SearchService();
        $word = "Artificial";
        $result = $searchService->search($post, $word);
        $this->assertNotEmpty($result);

    }
    /** test function which  search about word  in tags 
     * @return void
     */
    public function testSearchTag()
    {
        $tag = tag::factory()->create(['description' => ' testnewTag2kk']);
        $searchService = new SearchService();
        $word = "testnew";
        $result = $searchService->searchTag($word);
        $this->assertNotEmpty($result);
        $tag->delete();
    }
    /** test function which  search about word  in blogs 
     * @return void
     */
    public function testSearchBlog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(["user_id" => $user->id ,"username" => "radwaahmed11"]);
        $searchService = new SearchService();
        $word = "radwa";
        $result = $searchService->searchBlog($word);
        $this->assertNotEmpty($result);
        $user->delete();
        $blog->delete();
    }
}
