<?php

namespace Database\Seeders;

use App\Models\FollowBlog;
use Illuminate\Database\Seeder;

class FollowBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FollowBlog::factory(5)->create();
    }
}
