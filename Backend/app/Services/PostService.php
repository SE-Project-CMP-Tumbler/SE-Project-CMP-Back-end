<?php

namespace App\Services;

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
}
