<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Submission;
use App\Models\User;
use App\Models\FollowBlog;
use App\Models\Reply;
use App\Models\Like;

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
     * Determine whether the user can create reblog on this parent post.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createReblog(User $user, Blog $blog, Post $parentPost)
    {
        return $blog->user_id == $user->id && $parentPost->status == 'published';
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
     *  check if the user can add a reply on this post
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canReply(User $user, Post $post)
    {
        //the blog that wants to ad a reply
        $blog =  $user->blogs()->where('is_primary', true)->first();
        //check if he is the owner

        if ($post->blog->id == $blog->id) {
            return true;
        } else {
            //check everyone
            if ($post->blog->replies_settings == 'Everyone can reply') {
                return true;
            } elseif ($post->blog->replies_settings ==  'Only Tumblrs you follow can reply') {
            //check he follows me
                return !empty($post->blog->followings()->where('id', $blog->id)->first());
            } elseif ($post->blog->replies_settings ==  'Tumblrs you follow and Tumblrs following you for a week can reply') {
            //check 2-way follow in 1-week
                $blogFollowing = FollowBlog::where([
                    ['follower_id',$post->blog->id],
                    ['followed_id', $blog->id],
                    ['created_at' , '<' , now()->addDay(7)]])->first();
                $blogFollower =  FollowBlog::where([
                    ['follower_id',$blog->id],
                    ['followed_id', $post->blog->id],
                    ['created_at' , '<' , now()->addDay(7)]])->first();
                if ($blogFollowing || $blogFollower) {
                    return true;
                }
                return false;
            } else {
                return false;
            }
        }
    }
    /**
     *  check if the user can add a like on this post
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canLike(User $user, Post $post)
    {
        $blog =  $user->blogs()->where('is_primary', true)->first();
        return empty($blog->likes()->where('post_id', $post->id)->first());
    }
    /**
     *  check if the user can delete a reply on this post
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canDeleteReply(User $user, Reply $reply)
    {
        $blog =  $user->blogs()->where('is_primary', true)->first();
        return $reply->blog_id == $blog->id ;
    }
    /**
     *  check if the user can delete a like on this post
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function canDeleteLike(User $user, Like $like)
    {
        $blog =  $user->blogs()->where('is_primary', true)->first();
        return $like->blog_id == $blog->id ;
    }
}
