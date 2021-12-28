<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Blog;
use App\Models\User;
use App\Models\FollowBlog;
use Illuminate\Support\Facades\DB;
use App\Http\Misc\Helpers\Errors;
use App\Models\Post;
use App\Models\BlogFollowTag;
use App\Http\Resources\PostResource;
use App\Http\Resources\QuestionResource;

class AskService
{
    public function mergeAndPaginate($askedQuestions, $submissionPosts, $currentPage)
    {

        $currentPage = (is_numeric($currentPage) && $currentPage > 0) ? $currentPage : 1 ;
        $merged = ($askedQuestions->merge($submissionPosts))->sortByDesc("created_at");
        $result =  $merged->slice(($currentPage * 10 - 10), 10)->all();
         //dd($result);
         $arr = array();
        foreach ($result as $r) {
            if (($r instanceof Post)) {
                array_push($arr, new PostResource($r));
            } else {
                array_push($arr, new QuestionResource($r));
            }
        }
        return [
            "messages" => $arr,
             "pagination" => (object)[
                                        "total" => sizeof($merged),
                                        "count" => sizeof($arr),
                                        "per_page" => 10,
                                        "current_page" => (int)$currentPage,
                                        "total_pages" => ceil(sizeof($merged) / 10)]
                ];
    }
}
