<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\User;
use App\Services\BlogService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /** @var User $follower the user made the follow */
    protected $follower;

    /** @var User $followed the user that was followed */
    protected $followed;

    /** @var Blog $blog the blog that had been followed */
    protected $followedBlog;

    /** @var string $type the type of this notification */
    protected $type = 'follow';

    /** @var array $data this notification data */
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $follower, User $followed, Blog $followedBlog)
    {
        $this->follower = $follower;
        $this->followed = $followed;
        $this->followedBlog = $followedBlog;
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
        return new Channel('channel-' . $this->followed->id);
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
        $res = ["notification_id" => $this->id, "timestamp" => now(), "read_at" => null];
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
        $blogService = new BlogService();
        $check = $blogService->checkIsFollowed($this->followedBlog->id, $from_blog->id);
        return [
            // notification info
            'type' => $this->type,

            // the follower/user info
            'follower_id' => $this->follower->id,

            // the blog received the notification
            'target_blog_id' => $this->followedBlog->id,
            'followed' => $check,

            // the blog made the notifiable action
            'from_blog_id' => $from_blog->id,
            'from_blog_username' => $from_blog->username,
            'from_blog_avatar' => $from_blog->avatar,
            'from_blog_avatar_shape' => $from_blog->avatar_shape,
        ];
    }
}
