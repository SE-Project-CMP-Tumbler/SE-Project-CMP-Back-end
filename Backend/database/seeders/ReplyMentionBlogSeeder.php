<?php

namespace Database\Seeders;

use App\Models\ReplyMentionBlog;
use Illuminate\Database\Seeder;

class ReplyMentionBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReplyMentionBlog::factory(5)->create();
    }
}
