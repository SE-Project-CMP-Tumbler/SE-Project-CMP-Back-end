<?php

namespace App\Services;

use App\Http\Misc\Helpers\Config;
use App\Models\Blog;
use App\Models\Post;

/**
 * PostServices handles any logic associated with posts.
 */
class PostService
{
    /**
     * Extracting the tags from the post body.
     *
     * @param string $postBody the content of the post.
     *
     * @return string[] Array of the tags extracted.
     */
    public function extractTags($postBody)
    {
        //extracting the tags associated with the post being created
        $tags = array();
        //iterate through the whole body content
        for ($bodyIndex = 0; $bodyIndex < strlen($postBody); $bodyIndex++) {
            //search for a # and the word immediatly following it
            if ($postBody[$bodyIndex] == '#') {
                $extractedTag = '';
                $charIndex = $bodyIndex + 1;
                $possibleEndings = [' ', '&', '<'];
                while ($charIndex < strlen($postBody) && !in_array($postBody[$charIndex], $possibleEndings)) {
                    $extractedTag .= $postBody[$charIndex];
                    $charIndex += 1;
                }
                //append to the extracted tags array
                array_push($tags, $extractedTag);
            }
        }
        return $tags;
    }

    /**
     * Extract the blogs mentioned inside a posts' content.
     *
     * @var string $postBody The content of the post.
     *
     * @return string[] Array of the mentioned blogs.
     */
    public function extractMentionedBlogs($postBody)
    {
        $mentionedBlogs = array();
        for ($bodyIndex = 0; $bodyIndex < strlen($postBody); $bodyIndex++) {
            if ($postBody[$bodyIndex] == '@') {
                $blogUsernameTrackingIndex = $bodyIndex + 1;
                $mentionedBlog = "";
                $possibleEndings = [' ', '&', '<'];
                while ($blogUsernameTrackingIndex < strlen($postBody) && !in_array($postBody[$blogUsernameTrackingIndex], $possibleEndings)) {
                    $mentionedBlog .= $postBody[$blogUsernameTrackingIndex];
                    $blogUsernameTrackingIndex += 1;
                }
                array_push($mentionedBlogs, $mentionedBlog);
            }
        }
        return $mentionedBlogs;
    }

    /**
     * Extracting the removed tags from the newly updated post body.
     *
     * @param string[] $oldTags List of tags contained inside the old post's body.
     * @param string[] $newTags List of tags contained inside the updated post's body.
     * @return string[] Array of the removed tags.
     */
    public function getRemovedTags($oldTags, $newTags)
    {
        $removedTags = array();
        foreach ($oldTags as $oldTag) {
            if (!in_array($oldTag, $newTags)) {
                array_push($removedTags, $oldTag);
            }
        }
        return $removedTags;
    }

    /**
     * pin a post in the certain blog
     *
     * @param int $blogId
     * @param int $postId
     * @return bool
     **/
    public function pinPostService($blogId, $postId)
    {
        // 1. change the current pinned post to be unpinned
        Post::where([
            ['blog_id', '=', $blogId],
            ['pinned', '=', true]
        ])->update([
            'pinned' => false
        ]);

        // 2. pin the post in that blog
        Post::where([
            ['id', '=', $postId],
            ['blog_id', '=', $blogId],
        ])->update([
            'pinned' => true
        ]);

        return true;
    }

    /**
     * unpin a post in the certain blog
     *
     * @param int $blogId
     * @param int $postId
     * @return bool
     **/
    public function unpinPostService($blogId, $postId)
    {
        // 1. unpin the post in that blog
        Post::where([
            ['id', '=', $postId],
            ['blog_id', '=', $blogId],
            ['pinned', '=', true]
        ])->update([
            'pinned' => false
        ]);
        return true;
    }

    /**
     * change post status to one of three types private, draft or published
     *
     * @param int $blogId
     * @param int $postId
     * @param string $newStatus
     * @return bool
     **/
    public function changePostStatusService($blogId, $postId, $newStatus)
    {
        // get that post and update its status to the new one
        Post::where([
            ['id', '=', $postId],
            ['blog_id', '=', $blogId],
        ])->update([
            'status' => $newStatus,
        ]);
        return true;
    }
    /**
     * Get profile posts of a blog
     *
     * @param \Blog $blog The blog to retrieve his profile posts.
     * @return \Post[]
     */
    public function getProfilePosts($blog)
    {
        $blogId = $blog->id;
        $authUser = auth('api')->user();

        //For guests or blogs that doesn't own the profile to be retrieved
        if (empty($authUser) || $authUser->id != $blog->user_id) {
            $nonPinnedPosts = Post::where(function ($query) use ($blogId) {
                $query->where([
                    ['blog_id', $blogId],
                    ['status', 'published'],
                    ['approving_blog_id', null],
                    ['pinned', false]]);
            })->orWhere(function ($query) use ($blogId) {
                    $query->where([
                        ['approving_blog_id', $blogId],
                        ['status', 'published'],
                        ['pinned', false]]);
            })->orderBy('published_at', 'desc');

            $pinnedPost = Post::where([
                    ['blog_id', $blogId],
                    ['status', 'published'],
                    ['pinned', true]]);

            $res = $pinnedPost->unionAll($nonPinnedPosts)
                ->paginate(Config::PAGINATION_LIMIT);

            return $res;
        } else {
            $nonPinnedPosts = Post::where(function ($query) use ($blogId) {
                $query->where([
                    ['blog_id', $blogId],
                    ['approving_blog_id', null],
                    ['pinned', false]])
                    ->where(function ($query) {
                        $query->where('status', 'published')
                            ->orWhere('status', 'private');
                    });
            })->orWhere(function ($query) use ($blogId) {
                    $query->where([
                        ['approving_blog_id', $blogId],
                        ['pinned', false]])
                    ->where(function ($query) {
                        $query->where('status', 'published')
                            ->orWhere('status', 'private');
                    });
            })->orderBy('published_at', 'desc');

            $pinnedPost = Post::where([
                    ['blog_id', $blogId],
                    ['pinned', true]])
                    ->whereIn('status', ['published', 'private']);

            $res = $pinnedPost->unionAll($nonPinnedPosts)
                ->paginate(Config::PAGINATION_LIMIT);

            return $res;
        }
    }
    /**
     * Get List of parent posts, ordered from the most direct parent, till the grand that has no parent
     *
     * @param \Post $post post to get its parents' posts
     * @return \Post[] List of parents' posts
     */
    public function getTracedbackParentPosts($post)
    {
        $tracedParentPosts = [];
        $currentParent = $post->parentPost;
        while (!empty($currentParent)) {
            array_push($tracedParentPosts, $currentParent);
            $currentParent = $currentParent->parentPost;
        }
        return $tracedParentPosts;
    }
}
