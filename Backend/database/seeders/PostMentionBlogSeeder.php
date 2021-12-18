<?php

namespace Database\Seeders;

use App\Models\PostMentionBlog;
use Illuminate\Database\Seeder;

class PostMentionBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostMentionBlog::factory(5)->create();
    }
}
