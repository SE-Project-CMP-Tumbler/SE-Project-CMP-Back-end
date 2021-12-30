<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
   /**
     * Determine whether the user can answer this question or not.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canAnswer(User $user, Question $question)
    {
        return Blog::find($question->ask_reciever_blog_id)->user->id == $user->id;
    }
    /**
     * Determine whether the user can delete this question or not.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canDeleteAsk(User $user, Question $question)
    {
        return Blog::find($question->ask_reciever_blog_id)->user->id == $user->id;
    }
}
