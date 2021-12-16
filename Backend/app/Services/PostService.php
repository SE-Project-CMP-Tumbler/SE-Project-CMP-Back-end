<?php

namespace App\Services;

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
}
