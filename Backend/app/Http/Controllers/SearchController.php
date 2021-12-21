<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Like;
use App\Models\Post;
use App\Http\Misc\Helpers\Config;
use App\Http\Misc\Helpers\Success;
use App\Http\Resources\BlogResource;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\TagCollection;
use App\Http\Resources\PostCollection;
use App\Http\Requests\BlogRequest;
use App\Services\BlogService;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
     /**
 * @OA\Get(
 * path="/search/{word}",
 * summary="Search about word in blogs or in posts",
 * description=" Get all blogs which contains word or posts which contains word",
 * operationId="search",
 * tags={"Search"},
 * *  @OA\Parameter(
 *          name="word",
 *          description="Word to Search about",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object", example={"posts":
 * 
 *        
 *                  @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/posts/{blog_id}?page=1"),),
 *          @OA\Property(property="posts",type="array",
 *                  @OA\Items(
 *                    @OA\Property(property="post_id", type="integer", example=5),
 *                     @OA\Property(property="post_status", type="string", example="published"),
 *                     @OA\Property(property="post_type", type="string", example="general"),
 *                     @OA\Property(property="post_body", type="string", example="<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                     @OA\Property(property="blog_id", type="integer", example=5),
 *                     @OA\Property(property="blog_username", type="string", example=""),   
 *                     @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                     @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                     @OA\Property(property="blog_title", type="string", example=""),
 *                     @OA\Property(property="post_time",type="date_time",example="2012-02-30"),),)
 *          ,"tags": 
 *                @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=120),
 *                  @OA\Property(property="count",type="int",example=10),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=12),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="last_page_url",type="int",example=12),
 *                  @OA\Property(property="next_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=3"),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/tag/trending?page=1"),),
 *          @OA\Property(property="tags",type="array",
 *              @OA\Items(
 *                  @OA\Property(property="tag_description",type="string",example="books"),
 *                  @OA\Property(property="tag_image",type="string",format="byte",example=""),),)
 *     ,"blogs":
 *         @OA\Property(property="pagination",type="object",example={"total": 1,"count": 1,"per_page": 10, "current_page": 1,"total_pages": 1,"first_page_url": true,
 *             "last_page_url": 1,
 *             "next_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=3",
 *             "prev_page_url":  "http://127.0.0.1:8000/api/blogs/check_out_blogs?page=1"}),
 *           @OA\Property(property="blogs",type="array",
 *             @OA\Items(
 *                    @OA\Property(property="id", type="integer", example=2026),
 *                    @OA\Property(property="username", type="string", example="newinvestigations"),
 *                    @OA\Property(property="avatar", type="string", format="byte", example=""),
 *                    @OA\Property(property="avatar_shape", type="string", example="square"),
 *                    @OA\Property(property="header_image", type="string", format="byte", example=""),
 *                    @OA\Property(property="title", type="string", example="My 1st Blog"),
 *                    @OA\Property(property="description", type="string", example="This blog is a sketch of thoughts"),))
 * 
 *   }
 *        ),
 *     ),
 * ),
 * 
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *
 */
    public function search(Request $request, $word)
    {
        $posts = Post::all();
        $searchService =  new SearchService();
        $foundPosts = $searchService->search($posts, $word);
        $tags = $searchService->searchTag($word);
        $blogs = $searchService->searchBlog($word);
        return $this->generalResponse([
            "posts" => new PostCollection($foundPosts),
            "blogs" => new BlogCollection($blogs),
            "tags" => new TagCollection($tags)
        ], "ok");
    }
    /**
 * @OA\Get(
 * path="/search/{blog_id}/{word}",
 * summary="Search about word in blogs or in posts",
 * description=" Get all blogs which contains word or posts which contains word",
 * operationId="search",
 * tags={"Search"},
 * *  @OA\Parameter(
 *          name="word",
 *          description="Word to Search about",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string")),
 *
 * @OA\Response(
 *    response=200,
 *    description="Successful  response",
 *    @OA\JsonContent(
 *      @OA\Property(property="meta",type="object",example={ "status": "200","msg": "OK"}),
 *      @OA\Property(property="response",type="object",
 *           @OA\Property(property="pagination",type="object",
 *                  @OA\Property(property="total",type="int",example=17),
 *                  @OA\Property(property="count",type="int",example=7),
 *                  @OA\Property(property="per_page",type="int",example=10),
 *                  @OA\Property(property="current_page",type="int",example=2),
 *                  @OA\Property(property="total_pages",type="int",example=2),
 *                  @OA\Property(property="first_page_url",type="boolean",example=false),
 *                  @OA\Property(property="next_page_url",type="string",example=null),
 *                  @OA\Property(property="prev_page_url",type="string",example="http://127.0.0.1:8000/api/posts/{blog_id}?page=1"),),
 *          @OA\Property(property="posts",type="array",
 *                  @OA\Items(
 *                    @OA\Property(property="post_id", type="integer", example=5),
 *                     @OA\Property(property="post_status", type="string", example="published"),
 *                     @OA\Property(property="post_type", type="string", example="general"),
 *                     @OA\Property(property="post_body", type="string", example="<div><h1>What's Artificial intellegence? </h1> <img src='https://modo3.com/thumbs/fit630x300/84738/1453981470/%D8%A8%D8%AD%D8%AB_%D8%B9%D9%86_Google.jpg' alt=''><p>It's the weapon that'd end the humanity!!</p><video width='320' height='240' controls><source src='movie.mp4' type='video/mp4'><source src='movie.ogg' type='video/ogg'> Your browser does not support the video tag.</video><p>#AI #humanity #freedom</p></div>"),
 *                     @OA\Property(property="blog_id", type="integer", example=5),
 *                     @OA\Property(property="blog_username", type="string", example=""),   
 *                     @OA\Property(property="blog_avatar", type="string", format="byte", example=""),
 *                     @OA\Property(property="blog_avatar_shape", type="string", example=""),
 *                     @OA\Property(property="blog_title", type="string", example=""),
 *                     @OA\Property(property="post_time",type="date_time",example="2012-02-30"),),)
 *        ),
 *     ),
 * ),
 *  @OA\Response(
 *    response=404,
 *    description="Not found",
 *    @OA\JsonContent(
 *       @OA\Property(property="meta", type="object", example={"status": "404", "msg":"post is not found"})
 *        )
 *     )
 * )
 *
 */

    public function searchBlog(Request $request, $blogId, $word)
    {
        if (preg_match('(^[0-9]+$)', $blogId) == false) {
            return $this->generalResponse("", "The blog id should be numeric.", "422");
        }
        $blogService = new BlogService();
        $user = $request->user();
        $postStatus = ['published','private'];
        $blog = $blogService->findBlog($blogId);
        if ($blog == null) {
            return $this->generalResponse("", "Not Found blog", "404");
        }
        if ($user == null || $user->id != $blog->user->id) {
            $posts = $blog->posts->where('status', 'published');
        } elseif ($user->id == $blog->user->id) {
            $posts = $blog->posts->whereIn('status', $postStatus);
        }
        if ($posts == null) {
            return $this->generalResponse("", "Not  Matched Results", "422");
        }
        $searchService =  new SearchService();
        $matchedResult = $searchService->search($posts, $word);
        return $this->generalResponse(new PostCollection($matchedResult), "ok");
    }
}
