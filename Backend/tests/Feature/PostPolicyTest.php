<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing the authorization of a user to create a post
     *
     * @return void
     */
    public function testUserCanCreateOwningPost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('create', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization of a user to create a post
     *
     * @return void
     */
    public function testUserCanNotCreateNonOwningPost()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($userGuest->can('create', [Post::class, $blog]));
    }
    /**
     * Testing the authorization of a user to delete a post he/she created and was not submitted to another blog. 
     *
     * @return void
     */
    public function testUserCanDeleteOwningPost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($user->can('delete', [Post::class, $post]));
    }
    /**
     * Testing the non authorization of a user to delete a post he/she didn't create. 
     *
     * @return void
     */
    public function testUserCanNotDeleteNonOwningPost()
    {
        //Create the user whose post will be tended to be deleted by another user
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        //Create the user on which the authorization will be tested
        $userGuest = User::factory()->create();
        $this->assertFalse($userGuest->can('delete', [Post::class, $post]));
    }
    /**
     * Testing the authorization of a user to delete a submission post he/she recieved, but not yet approved.
     *
     * @return void
     */
    public function testUserCanDeleteRecievedSubmissionPost()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        $submittedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'status' => 'submission'
        ]);

        //Create the user who recieve the submission post request
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create(['user_id' => $recieverUser->id]);

        //Create the submission relation
        $submission = Submission::create([
            'submitter_id' => $submitterBlog->id,
            'reciever_id' => $recieverBlog->id,
            'post_id' => $submittedPost->id,
        ]);
        $this->assertTrue($recieverUser->can('delete', [Post::class, $submittedPost]));
    }
    /**
     * Testing the non authorization of a user to delete a submission post he/she created on another user. 
     *
     * @return void
     */
    public function testUserCanNotDeleteOwnedSubmissionPost()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        $submittedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'status' => 'submission'
        ]);

        //Create the user who recieve the submission post request
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create(['user_id' => $recieverUser->id]);

        //Create the submission relation
        $submission = Submission::create([
            'submitter_id' => $submitterBlog->id,
            'reciever_id' => $recieverBlog->id,
            'post_id' => $submittedPost->id,
        ]);
        $this->assertFalse($submitterUser->can('delete', [Post::class, $submittedPost]));
    }
    /**
     * Testing the authorization of a user to delete a submission post he/she recieved and approved.
     *
     * @return void
     */
    public function testUserCanDeleteRecievedApprovedSubmissionPost()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        //Create the user who approved the submission post
        $approverUser = User::factory()->create();
        $approverBlog = Blog::factory()->create(['user_id' => $approverUser->id]);
        //Create the submitted post
        $submittedApprovedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'approving_blog_id' => $approverBlog->id
        ]);
        $this->assertTrue($approverUser->can('delete', [Post::class, $submittedApprovedPost]));
    }
    /**
     * Testing the non authorization of a user to delete a submission post he/she created on another user and where approved by the recieved user. 
     *
     * @return void
     */
    public function testUserCanNotDeleteOwnedSubmissionPostThatWasApproved()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        //Create the user who approved the submission post
        $approverUser = User::factory()->create();
        $approverBlog = Blog::factory()->create(['user_id' => $approverUser->id]);
        //Create the submitted post
        $submittedApprovedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'approving_blog_id' => $approverBlog->id
        ]);
        $this->assertFalse($submitterUser->can('delete', [Post::class, $submittedApprovedPost]));
    }
    /**
     * Testing the authorization of a user to update a post he/she created and was not submitted to another blog. 
     *
     * @return void
     */
    public function testUserCanUpdateOwningPost()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);
        $this->assertTrue($user->can('update', [Post::class, $post]));
    }
    /**
     * Testing the non authorization of a user to update a post he/she didn't create. 
     *
     * @return void
     */
    public function testUserCanNotUpdateNonOwningPost()
    {
        //Create the user whose post will be tended to be updated by another user
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        //Create the user on which the authorization will be tested
        $userGuest = User::factory()->create();
        $this->assertFalse($userGuest->can('update', [Post::class, $post]));
    }
    /**
     * Testing the non authorization of a user to update a submission post he/she recieved, but not yet approved.
     *
     * @return void
     */
    public function testUserCanNotUpdateRecievedSubmissionPostNotApproved()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        $notYetApprovedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'status' => 'submission'
        ]);

        //Create the user who recieve the submission post request
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create(['user_id' => $recieverUser->id]);

        //Create the submission relation
        $submission = Submission::create([
            'submitter_id' => $submitterBlog->id,
            'reciever_id' => $recieverBlog->id,
            'post_id' => $notYetApprovedPost->id,
        ]);
        $this->assertFalse($recieverUser->can('update', [Post::class, $notYetApprovedPost]));
    }
    /**
     * Testing the non authorization of a user to update a submission post he/she created on another user. 
     *
     * @return void
     */
    public function testUserCanNotUpdateOwnedSubmissionPost()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        $submittedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'status' => 'submission'
        ]);

        //Create the user who recieve the submission post request
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create(['user_id' => $recieverUser->id]);

        //Create the submission relation
        $submission = Submission::create([
            'submitter_id' => $submitterBlog->id,
            'reciever_id' => $recieverBlog->id,
            'post_id' => $submittedPost->id,
        ]);
        $this->assertFalse($submitterUser->can('update', [Post::class, $submittedPost]));
    }
    /**
     * Testing the authorization of a user to update a submission post he/she recieved and approved.
     *
     * @return void
     */
    public function testUserCanUpdateRecievedApprovedSubmissionPost()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        //Create the user who approved the submission post
        $approverUser = User::factory()->create();
        $approverBlog = Blog::factory()->create(['user_id' => $approverUser->id]);
        //Create the submitted post
        $submittedApprovedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'approving_blog_id' => $approverBlog->id
        ]);
        $this->assertTrue($approverUser->can('update', [Post::class, $submittedApprovedPost]));
    }
    /**
     * Testing the non authorization of a user to update a submission post he/she created on another user and where approved by the recieved user. 
     *
     * @return void
     */
    public function testUserCanNotUpdateOwnedSubmissionPostThatWasApproved()
    {
        //Create the user who created a submission post
        $submitterUser = User::factory()->create();
        $submitterBlog = Blog::factory()->create(['user_id' => $submitterUser->id]);
        //Create the user who approved the submission post
        $approverUser = User::factory()->create();
        $approverBlog = Blog::factory()->create(['user_id' => $approverUser->id]);
        //Create the submitted post
        $submittedApprovedPost = Post::factory()->create([
            'blog_id' => $submitterBlog->id,
            'approving_blog_id' => $approverBlog->id
        ]);
        $this->assertFalse($submitterUser->can('update', [Post::class, $submittedApprovedPost]));
    }
    /**
     * Testing the authorization of a user to get draft posts.
     *
     * @return void
     */
    public function testUserCanViewDraftPosts()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'blog_id' => $blog->id,
            'status' => 'draft'
        ]);
        $this->assertTrue($user->can('viewDraftPosts', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization of a user to get draft posts.
     *
     * @return void
     */
    public function testUserCanNotViewDraftPosts()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'blog_id' => $blog->id,
            'status' => 'draft'
        ]);
        $this->assertFalse($userGuest->can('viewDraftPosts', [Post::class, $blog]));
    }
    /**
     * Testing the authorization of a user to view submission posts.
     * User is allowed to view posts requested to be submissited on a blog the user own.
     *
     * @return void
     */
    public function testUserCanViewSubmissionPosts()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('viewSubmissionPosts', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization of a user to view submission posts.
     * User is not allowed to view posts requested to be submissited on a blog the user doesn't own.
     *
     * @return void
     */
    public function testUserCanNotViewSubmissionPosts()
    {
        $user = User::factory()->create();
        $userGuest = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($userGuest->can('viewSubmissionPosts', [Post::class, $blog]));
    }
    /**
     * Testing the authorization of a user to create a reblog on a parent with status published.
     *
     * @return void
     */
    public function testUserCanCreateReblogOnPublishedParentPost()
    {
        //Create a published parent post
        $userOfParentPost = User::factory()->create();
        $blogOfParentPost = Blog::factory()->create(['user_id' => $userOfParentPost->id]);
        $parentPost = Post::factory()->create([
            'blog_id' => $blogOfParentPost->id,
            'status' => 'published'
        ]);
        //Create the user on which the authorization will be tested
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('createReblog', [Post::class, $blog, $parentPost]));
    }
    /**
     * Testing the non authorization of a user to create a reblog on a parent with status other than published.
     *
     * @return void
     */
    public function testUserCanNotCreateReblogOnNonPublishedParentPost()
    {
        //Create a non-published parent post
        $userOfParentPost = User::factory()->create();
        $blogOfParentPost = Blog::factory()->create(['user_id' => $userOfParentPost->id]);
        $parentPost = Post::factory()->create([
            'blog_id' => $blogOfParentPost->id,
            'status' => 'draft'
        ]);
        //Create the user on which the authorization will be tested
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertFalse($user->can('createReblog', [Post::class, $blog, $parentPost]));
    }
    /**
     * Testing the authorization of a user to reblog on the behalf of a blog the user own.
     *
     * @return void
     */
    public function testUserCanCreateReblogViaOwnedBlog()
    {
        //Create a published parent post
        $userOfParentPost = User::factory()->create();
        $blogOfParentPost = Blog::factory()->create(['user_id' => $userOfParentPost->id]);
        $parentPost = Post::factory()->create([
            'blog_id' => $blogOfParentPost->id,
            'status' => 'published'
        ]);

        //Create the user on which the authorization will be tested
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->can('createReblog', [Post::class, $blog, $parentPost]));
    }
    /**
     * Testing the non authorization of a user to reblog on the behalf of a blog the user doesn't own.
     *
     * @return void
     */
    public function testUserCanNotCreateReblogViaNonOwnedBlog()
    {
        //Create a published parent post
        $userOfParentPost = User::factory()->create();
        $blogOfParentPost = Blog::factory()->create(['user_id' => $userOfParentPost->id]);
        $parentPost = Post::factory()->create([
            'blog_id' => $blogOfParentPost->id,
            'status' => 'published'
        ]);

        //Create the user on his behalf the reblog will be created
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        //Create the user on which the authorization will be tested
        $userGuest = User::factory()->create();

        $this->assertFalse($userGuest->can('createReblog', [Post::class, $blog, $parentPost]));
    }
    /**
     * Testing the authorization to create submission on a blog that allow submissions.
     *
     * @return void
     */
    public function testUserCanCreateSubmissionOnBlogAllowingSubmissions()
    {
        //Create the user that recieves the submissions.
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create([
            'user_id' => $recieverUser->id,
            'allow_submittions' => true
        ]);

        //Create the user on which the authorization will be tested
        $submitterUser = User::factory()->create();
        $this->assertTrue($submitterUser->can('createSubmission', [Post::class, $recieverBlog]));
    }
    /**
     * Testing non authorization to create submission on a blog that doesn't allow submissions.
     *
     * @return void
     */
    public function testUserCanCreateSubmissionOnBlogNotAllowingSubmissions()
    {
        //Create the user that recieves the submissions.
        $recieverUser = User::factory()->create();
        $recieverBlog = Blog::factory()->create([
            'user_id' => $recieverUser->id,
            'allow_submittions' => false
        ]);

        //Create the user on which the authorization will be tested
        $submitterUser = User::factory()->create();
        $this->assertFalse($submitterUser->can('createSubmission', [Post::class, $recieverBlog]));
    }
    /**
     * Testing the authorization to approve submission requested on one of the user's owned blogs.
     *
     * @return void
     */
    public function testUserCanApproveSubmissionOnOwningBlog()
    {
        //Create the user on which the authorization will be tested
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->can('approveSubmission', [Post::class, $blog]));
    }
    /**
     * Testing the non authorization to approve submission requested on a blog the user doesn't own.
     *
     * @return void
     */
    public function testUserCanNotApproveSubmissionOnNonOwningBlog()
    {
        //Create the user on his behalf the submissions is tended to be approved
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        //Create the user on which the authorization will be tested
        $userGuest = User::factory()->create();
        $this->assertFalse($userGuest->can('approveSubmission', [Post::class, $blog]));
    }
}
