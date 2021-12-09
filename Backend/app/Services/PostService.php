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
     * @param string $post_body the content of the post.
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
     * @param int $blog_id
     * @param int $post_id
     * @return bool
     **/
    public function pinPostService($blog_id, $post_id)
    {
        // 1. change the current pinned post to be unpinned
        Post::where([
            ['blog_id', '=', $blog_id],
            ['pinned', '=', true]
        ])->update([
            'pinned' => false
        ]);

        // 2. pin the post in that blog
        Post::where([
            ['id', '=', $post_id],
            ['blog_id', '=', $blog_id],
        ])->update([
            'pinned' => true
        ]);

        return true;
    }

    /**
     * unpin a post in the certain blog
     *
     * @param int $blog_id
     * @param int $post_id
     * @return bool
     **/
    public function unpinPostService($blog_id, $post_id)
    {
        // 1. unpin the post in that blog
        Post::where([
            ['id', '=', $post_id],
            ['blog_id', '=', $blog_id],
            ['pinned', '=', true]
        ])->update([
            'pinned' => false
        ]);
        return true;
    }

    /**
     * change post status to one of three types private, draft or published
     *
     * @param int $blog_id
     * @param int $post_id
     * @param string $new_status
     * @return bool
     **/
    public function changePostStatusService($blog_id, $post_id, $new_status)
    {
        // get that post and update its status to the new one
        Post::where([
            ['id', '=', $post_id],
            ['blog_id', '=', $blog_id],
        ])->update([
            'status' => $new_status,
        ]);
        return true;
    }
}
