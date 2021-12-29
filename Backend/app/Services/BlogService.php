<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Blog;
use App\Models\Theme;
use App\Models\User;
use App\Models\FollowBlog;
use Illuminate\Support\Facades\DB;
use App\Http\Misc\Helpers\Errors;
use App\Models\Block;
use App\Models\BlogFollowTag;

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

    public function createBlog(string $username, string $title, int $userId, string $password = null, $isPrimary = false)
    {
        $password = md5($password);
        DB::beginTransaction();
        try {
            $blog = Blog::create([
            'username' => $username ,
            'title' => $title ,
            'password' => $password ,
            'user_id' => $userId,
            'is_primary' => $isPrimary
            ]);
            Theme::create(['blog_id' => $blog->id]);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
             return false;
        }
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
  * Find blog by id
  * @param int $blogId
  * @return \Blog
 */
    public function findBlog(int $blogId)
    {
        $blog = Blog::find($blogId);
        return $blog;
    }
      /**
  * Find blog by username
  * @param string $blogUsername
  * @return \Blog
 */
    public function findBlogByUsername(string $blogUsername)
    {
        $blog = Blog::where('username', $blogUsername)->first();
        return $blog;
    }
    /**
     * Check if a blog is blocking another.
     *
     * @param int $blockerId The id of the blog that may have done the block action.
     * @param int $blockedId The id of the blog on which the block may be done.
     * @return bool
     */
    public function checkIsBlocking($blockerId, $blockedId)
    {
        $block = Block::where('blocker_id', $blockerId)
            ->where('blocked_id', $blockedId)
            ->first();
        return !empty($block);
    }
    /**
     * Check if a blog is following a specific tag.
     *
     * @param int $blogId The id of the blog.
     * @param int $tagDescription The description of the tag to check if following.
     * @return bool
     */
    public function checkIsFollowingTag($tagDescription)
    {
        $user = auth('api')->user();
        if (empty($user)) {
            // if non authenticated route is using this function
            // We'll return then that the guest is not following the tag
            return false;
        }
        $primaryBlog = $this->getPrimaryBlog($user);
        $followRelation = BlogFollowTag::where('blog_id', $primaryBlog->id)
            ->where('tag_description', $tagDescription)
            ->first();
        return !empty($followRelation);
    }
}
