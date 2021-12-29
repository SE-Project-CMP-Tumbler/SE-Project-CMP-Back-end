<?php

namespace App\Http\Misc\Helpers;

use App\Models\Answer;
use App\Models\Post;
use App\Models\Question;
use DOMDocument;

class NotificationHelper
{
    /**
     * get the post info (first image in the post and last paragraph)
     *
     * @return array
     **/
    public function extractPostSummary(Post $post, Post $parentPost = null)
    {
        $html = $post->body ? $post->body : $parentPost->body;
        return $this->extractFromHTML($html);
    }

    /**
     * get the ask info (last paragraph in question and last paragraph in answer)
     *
     * @return array
     **/
    public function extractQuestionSummary(Question $question, Answer $answer = null)
    {
        $questionParagraph = $this->extractFromHTML($question->body)[1];
        $answerParagraph = '';

        if ($answer) {
            $answerPost = Post::where('id', $answer->post_id)->first();
            if ($answerPost) {
                $answerParagraph = $this->extractFromHTML($answerPost->body)[1];
            }
        }

        return [$questionParagraph, $answerParagraph];
    }

    public function extractFromHTML($html)
    {
        $firstImageSrc = '';
        $lastParagraphText = '';
        try {
            $doc = new DOMDocument();
            if ($html) {
                $doc->loadHTML($html);
                $imgTags = $doc->getElementsByTagName('img');
                if ($imgTags->length > 0) {
                    $firstImage = $imgTags->item(0);
                    $firstImageSrc = $firstImage->getAttribute('src');
                    if ($firstImageSrc) {
                        $firstImage = trim(urldecode($firstImageSrc));
                    }
                }
                $pTags = $doc->getElementsByTagName('p');
                if ($pTags->length > 0) {
                    $lastParagraphText = trim($pTags->item($pTags->length - 1)->nodeValue);
                }
            }
        } catch (\Exception $e) {
        }
        return [$firstImageSrc, $lastParagraphText];
    }
}
