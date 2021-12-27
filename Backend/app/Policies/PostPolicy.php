<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view draft posts.
     *
     * @param  \App\Models\User $user
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewDraftPosts(User $user, Blog $blog)
    {
        return $blog->user_id == $user->id;
    }
    /**
     * Determine whether the user can view submission posts.
     *
     * @param  \App\Models\User $user
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewSubmissionPosts(User $user, Blog $blog)
    {
        return $blog->user_id == $user->id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Blog $blog)
    {
        return $blog->user_id == $user->id;
    }
    /**
     * Determine whether the user can create submission post request to a specific blog.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createSubmission(User $user, Blog $blog)
    {
        return $blog->allow_submittions == true;
    }
    /**
     * Determine whether the user can approve submission post.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approveSubmission(User $user, Blog $blog)
    {
        return $blog->user_id == $user->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post)
    {
        $submission = Submission::where('post_id', $post->id)->first();
        $isPostOwner = $post->blog->user_id == $user->id;
        $postApprover = $post->approver;

        if (!empty($submission)) {
            //If this post is a submission, and haven't been approved yet
            //No one can update it.
            return false;
        } elseif (!empty($postApprover)) {
            //If this post was a submission, and is approved
            //The its approver is the one authorized to update it
            return $postApprover->user_id == $user->id;
        } else {
            //Otherwise, the post owner is the one authorized to update it
            return $isPostOwner;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        $submission = Submission::where('post_id', $post->id)->first();
        $isPostOwner = $post->blog->user_id == $user->id;
        $postApprover = $post->approver;

        if (!empty($submission)) {
            //If this post is a submission, and haven't been approved yet
            //The its reciever is the one authorized to delete it
            return $submission->reciever->user_id == $user->id;
        } elseif (!empty($postApprover)) {
            //If this post was a submission, and is approved
            //The its approver is the one authorized to delete it
            return $postApprover->user_id == $user->id;
        } else {
            //Otherwise, the post owner is the one authorized to delete it
            return $isPostOwner;
        }
    }

    /**
     *
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function caReply(User $user, Post $post)
    {
        //check if he is the owner
        
        //check everyone

        //check 2-way follow in 1-week

        //check he follows me



        return $post->blog->user_id == $user->id;
    }
}
