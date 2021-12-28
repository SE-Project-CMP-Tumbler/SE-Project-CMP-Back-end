<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Http\Resources\BlogResource;

class UserFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // is the user id --
    // as the user can followe other blogs with only his primary blog
    protected $follower;

    // the type of the notification
    protected $type = 'follow';

    // this will store the data for the current notification
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $follower)
    {
        $this->follower = $follower;
        $this->data = $this->prepareData();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the event of the notification being broadcast
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-notification';
    }

    /**
     * Get the channel of the notification being broadcast
     *
     * @return string
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-' . auth()->user()->id);
    }

    /**
     * Get the type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType()
    {
        return $this->type;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->data;
    }

    /**
     * Get the array representation of the notification when broadcasting it
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $res = ["notification_id" => $this->id, "timestamp" => now()];
        $res += $this->data;
        return $res;
    }


    /**
     * prepare the data for the current notification
     *
     * @return array
     **/
    public function prepareData()
    {
        $from_blog = Blog::where('user_id', $this->follower->id)->first();
        $curUserPrimaryBlogID = auth()->user()->blogs()->first()->id;
        $blogService = new BlogService();
        $check = $blogService->checkIsFollowed($curUserPrimaryBlogID, $from_blog->id);
        return [
            'follower_id' => $this->follower->id,
            'type' => $this->type,
            'target_blog_id' => $curUserPrimaryBlogID,
            'from_blog_id' => $from_blog->id,
            'from_blog_username' => $from_blog->username,
            'from_blog_avatar' => $from_blog->avatar,
            'from_blog_avatar_shape' => $from_blog->avatar_shape,
            'from_blog_header_image' => $from_blog->header_image,
            'followed' => $check,
        ];
    }
}