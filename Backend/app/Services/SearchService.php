<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Search;
use App\Models\FollowBlog;
use App\Http\Misc\Helpers\Config;
use App\Http\Misc\Helpers\Success;
use Illuminate\Support\Facades\DB;
use App\Http\Misc\Helpers\Errors;
use App\Models\Block;

class SearchService
{
  /**
  * Search word in posts with their tags
  * @param $posts
  * @param $word
  * @return \Post
 */
    public function search($posts, $word)
    {
        $matchedResult = [];
        $word = strtolower($word);
        foreach ($posts as $post) {
              $tags =  $post->tags;
              $postBody = strip_tags($post->body);
              $foundWord = strstr($postBody, $word);
            if ($tags != null) {
                $result = $tags->where('description', 'like', '%' . $word . '%');
            }
            if (sizeof($result) > 0 || !empty($foundWord)) {
                array_push($matchedResult, $post->id);
            }
        }
        $posts = Post::whereIn('id', $matchedResult)->paginate(Config::PAGINATION_LIMIT);
        return $posts;
    }
    /**
  * Search word in tags
  * @param $word
  * @return \Tag
 */
    public function searchTag($word)
    {
        $word = strtolower($word);
        $result = Tag::where('description', 'like', '%' . $word . '%')->paginate(Config::PAGINATION_LIMIT);
        return $result;
    }
     /**
  * Search word in blogs
  * @param $word
  * @return \Tag
 */
    public function searchBlog($word)
    {
        $word = strtolower($word);
        $result = Blog::where('username', 'like', '%' . $word . '%')->paginate(Config::PAGINATION_LIMIT);
        return $result;
    }
       /**
  * add new word to search
  * @param $word
  * @return bool
 */
    public function createWord($word)
    {
        $word = strtolower($word);
        if (Search::where('word', $word)->count() > 0) {
              return true;
        }
        Search::create(['word' => $word]);
        return true;
    }
}