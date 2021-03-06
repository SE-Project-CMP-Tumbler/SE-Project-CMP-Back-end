<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(BlogSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(PostTagSeeder::class);
        $this->call(BlogFollowTagSeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(ReplySeeder::class);
        $this->call(FollowBlogSeeder::class);
        $this->call(PostMentionBlogSeeder::class);
        $this->call(ReplyMentionBlogSeeder::class);
        $this->call(SearchSeeder::class);
        $this->call(ChatMessageSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(AnswerSeeder::class);
        $this->call(ThemeSeeder::class);
    }
}
