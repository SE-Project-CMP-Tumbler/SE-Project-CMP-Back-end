<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Blog;
use App\Models\Question;
use Tests\TestCase;

class QuestionPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test answering questions asked to my blog
     *
     * @return void
     */
    public function testTruecanAnswerQuestionPolicy()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $blogSender = Blog::factory()->create(['user_id' => $user2->id]);
        $blogReciever = Blog::factory()->create(['user_id' => $user1->id]);
        $question = Question::factory()->create(['ask_sender_blog_id' => $blogSender->id, 'ask_reciever_blog_id' => $blogReciever->id]);
        $this->assertTrue($user1->can('canAnswer', $question));
    }
    /**
     * test deleting questions asked to my blog
     *
     * @return void
     */
    public function testTruecanDeleteAskQuestionPolicy()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $blogSender = Blog::factory()->create(['user_id' => $user2->id]);
        $blogReciever = Blog::factory()->create(['user_id' => $user1->id]);
        $question = Question::factory()->create(['ask_sender_blog_id' => $blogSender->id, 'ask_reciever_blog_id' => $blogReciever->id]);
        $this->assertTrue($user1->can('canDeleteAsk', $question));
    }
    /**
     * test answering questions asked to other blog
     *
     * @return void
     */
    public function testFalsecanAnswerQuestionPolicy()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $blogSender = Blog::factory()->create(['user_id' => $user2->id]);
        $blogReciever = Blog::factory()->create(['user_id' => $user1->id]);
        $question = Question::factory()->create(['ask_sender_blog_id' => $blogSender->id, 'ask_reciever_blog_id' => $blogReciever->id]);
        $this->assertFalse($user2->can('canAnswer', $question));
    }
    /**
     * test deleting questions asked to other blog
     *
     * @return void
     */
    public function testFalsecanDeleteAskQuestionPolicy()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $blogSender = Blog::factory()->create(['user_id' => $user2->id]);
        $blogReciever = Blog::factory()->create(['user_id' => $user1->id]);
        $question = Question::factory()->create(['ask_sender_blog_id' => $blogSender->id, 'ask_reciever_blog_id' => $blogReciever->id]);
        $this->assertFalse($user2->can('canDeleteAsk', $question));
    }
}
