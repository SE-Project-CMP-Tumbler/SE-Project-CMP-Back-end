<?php

namespace Database\Seeders;

use App\Models\BlogFollowTag;
use Illuminate\Database\Seeder;

class BlogFollowTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogFollowTag::factory(5)->create();
    }
}
