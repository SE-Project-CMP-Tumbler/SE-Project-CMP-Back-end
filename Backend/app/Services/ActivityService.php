<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ActivityService
{

    /**
     * extract the notes for the a specific blog
     *
     * @param int $blog_id
     * @param int $period
     * @param int $rate
     * @return array
     **/
    public function getNotesService(int $blog_id, int $period, int $rate)
    {
        $rate = ($period == 1 || $rate == 0) ? 'hour' : 'day';

        // the blog id in the replies table referes to the blog made the reply
        // need to relate each post with its blog id from the posts table
        $query = 'select
            date_trunc(\'' . $rate . '\', t2.created_at) as timestamp,
            count(*) as notes from
            (select t1.post_id, t1.created_at from 
                (select replies.post_id, replies.created_at from replies 
                union ( select likes.post_id, likes.created_at from likes)) as t1, posts 
                where t1.post_id = posts.id and posts.blog_id = ' . $blog_id . ') as t2 
             where created_at::date <= current_date and created_at::date >= current_date - interval \'' . $period . ' day\'
            group by date_trunc(\'' . $rate . '\', created_at)
            order by date_trunc(\'' . $rate . '\', created_at);';

        $data = DB::select($query);
        return $data;
    }

    /**
     * extract new followers a blog
     *
     * @param int $blog_id
     * @param int $period
     * @param int $rate
     * @return array
     **/
    public function getNewFollowersService(int $blog_id, int $period, int $rate)
    {
        $rate = ($period == 1 || $rate == 0) ? 'hour' : 'day';
        $query = 'select count(*) as new_followers,
            date_trunc(\'' . $rate . '\', created_at) as timestamp
            from follow_blog where followed_id = '
            . $blog_id .
            ' and created_at::date <= current_date and created_at::date >= current_date - interval \'' . $period . ' day\'
            group by date_trunc(\'' . $rate . '\', created_at)
            order by date_trunc(\'' . $rate . '\', created_at);';

        $data = DB::select($query);
        return $data;
    }

    /**
     * extract total followers for a blog
     *
     * @param int $blog_id
     * @param int $period
     * @param int $rate
     * @return array
     **/
    public function getTotalFollowersService(int $blog_id, int $period, int $rate)
    {
        $rate = ($period == 1 || $rate == 0) ? 'hour' : 'day';
        $query = 'select count(*) as total_followers,
            date_trunc(\'' . $rate . '\', created_at) as timestamp
            from follow_blog where followed_id = '
            . $blog_id .
            ' and created_at::date <= current_date and created_at::date >= current_date - interval \'' . $period . ' day\'
            group by date_trunc(\'' . $rate . '\', created_at)
            order by date_trunc(\'' . $rate . '\', created_at);';

        $data = DB::select($query);

        if ($data) {
            for ($i = 1; $i < count($data); $i++) {
                $data[$i]->total_followers += $data[$i - 1]->total_followers;
            }
        }
        return $data;
    }

    public function countNotesService(int $blog_id, int $period, int $rate)
    {
        $data = $this->getNotesService($blog_id, $period, $rate);
        $cnt = 0;
        if ($data) {
            for ($i = 0; $i < count($data); $i++) {
                $cnt += $data[$i]->notes;
            }
        }
        return $cnt;
    }

    public function countNewFollowersService(int $blog_id, int $period, int $rate)
    {
        $data = $this->getTotalFollowersService($blog_id, $period, $rate);
        $cnt = 0;
        if ($data) {
            $cnt = $data[count($data) - 1]->total_followers;
        }
        return $cnt;
    }

    public function countTotalFollowersService(int $blog_id, int $period, int $rate)
    {
        $data = $this->getTotalFollowersService($blog_id, $period, $rate);
        $cnt = 0;
        if ($data) {
            for ($i = 0; $i < count($data); $i++) {
                $cnt += $data[$i]->total_followers;
            }
        }
        return $cnt;
    }
}
