<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Blog;
use App\Models\User;
use App\Models\FollowBlog;
use Illuminate\Support\Facades\DB;
use App\Http\Misc\Helpers\Errors;

class BlogService
{
 /**
  * Create a new blog
  * @param string $username
  * @param string $password
  * @param int $user_id
  * @param string $title
  * @param boolean $is_primary
  * @return \Blog
 */

    public function createBlog(string $username, string $title, int $user_id, string $password = null, $is_primary = false)
    {
        $password = md5($password);
        Blog::create([
           'username' => $username ,
           'title' => $title ,
            'password' => $password ,
            'user_id' => $user_id,
            'is_primary' => $is_primary
        ]);
        return true;
    }
     /**
  * Check unique username of blog
  * @param string $username
  * @return boolean
 */

    public function uniqueBlog(string $username)
    {
        if (Blog::where('username', $username)->count() > 0) {
            return false;
        }
        return true;
    }
      /**
  * Check follow of blog
  * @param int $followedId
  *@param int $followerId
  * @return boolean
 */
    public function checkIsFollowed(int $followerId, int $followedId)
    {
        $follow = FollowBlog::where(['follower_id' => $followerId , 'followed_id' => $followedId])->count();
        if ($follow > 0) {
            return true;
        }
        return false;
    }
 /**
  * Check primary blog
  * @param \User $user
  *
  * @return \Blog
 */
    public function getPrimaryBlog(User $user)
    {
        $primaryBlog = $user->blogs->where('is_primary', true)->first();
        return $primaryBlog;
    }
    /**
  * Create follow blog
  * @param int $followerId
  * @param int $folllowedId
  * @return boolean
 */
    public function creatFollowBlog(int $followerId, int $folllowedId)
    {
        FollowBlog::create(['follower_id' => $followerId , 'followed_id' => $folllowedId]);
        return true;
    }
     /**
  * Delete follow blog
  * @param int $followerId
  * @param int $folllowedId
  * @return boolean
 */
    public function deleteFollowBlog(int $followerId, int $folllowedId)
    {
        FollowBlog::where(['follower_id' => $followerId , 'followed_id' => $folllowedId])->delete();
        return true;
    }
      /**
  * Find blog
  * @param int $blogId
  * @return \Blog
 */
    public function findBlog(int $blogId)
    {
        $blog = Blog::find($blogId);
        return $blog;
    }
}
