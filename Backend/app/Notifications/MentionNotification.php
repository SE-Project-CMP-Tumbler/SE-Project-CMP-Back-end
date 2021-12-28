<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\Post;
use App\Services\BlogService;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mentioner;
    protected $mentionedBlog;

    // the post the current user was mention at
    protected $post;

    // the type of the notification
    protected $type = 'mention';

    // this will store the data for the current notification
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Blog $mentioner, Blog $mentionedBlog, Post $post)
    {
        $this->mentioner = $mentioner;
        $this->mentionedBlog = $mentionedBlog;
        $this->post = $post;
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
        $blogService = new BlogService();
        $check = $blogService->checkIsFollowed($this->mentionedBlog->id, $this->mentioner->id);
        return [
            'target_post_id' => $this->post->id,
            'target_post_type' => $this->post->type,
            'target_post_summary' => '',
            'type' => $this->type,
            'target_blog_id' => $this->mentionedBlog->id,
            'from_blog_id' => $this->mentioner->id,
            'from_blog_username' => $this->mentioner->username,
            'from_blog_avatar' => $this->mentioner->avatar,
            'from_blog_avatar_shape' => $this->mentioner->avatar_shape,
            'from_blog_header_image' => $this->mentioner->header_image,
            'followed' => $check,
        ];
    }
}
