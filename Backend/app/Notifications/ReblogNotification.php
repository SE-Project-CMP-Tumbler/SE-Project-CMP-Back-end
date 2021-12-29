<?php

namespace App\Notifications;

use App\Models\Blog;
use App\Models\Post;
use App\Services\BlogService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Http\Misc\Helpers\NotificationHelper;

class ReblogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Blog $actorBlog the blog made the mention action */
    protected $actorBlog;

    /** @var Blog $recipientBlog the blog that was mentioned */
    protected $recipientBlog;

    /** @var Post $post the post which was rebloged */
    protected $parentPost;

    /** @var Post $post the post */
    protected $post;

    /** @var string $type the type of this notification */
    protected $type = 'reblog';

    /** @var array $data this notification data */
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Blog $actorBlog, Blog $recipientBlog, Post $parentPost, Post $post)
    {
        $this->actorBlog = $actorBlog;
        $this->recipientBlog = $recipientBlog;
        $this->parentPost = $parentPost;
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
        return new Channel('channel-' . auth()->user()->id);
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
        $check = $blogService->checkIsFollowed($this->recipientBlog->id, $this->actorBlog->id);
        list($firstImageSrc, $lastParagraphText) = (new NotificationHelper())->extractPostSummary($this->post, $this->parentPost);

        return [
            // notification info
            'type' => $this->type,

            // post info
            'target_post_id' => $this->post->id,
            'target_post_type' => $this->post->type,
            'target_post_summary' => $lastParagraphText,
            'target_post_image' => $firstImageSrc,

            // the blog received the notification
            'target_blog_id' => $this->recipientBlog->id,
            'followed' => $check,

            // the blog made the notifiable action
            'from_blog_id' => $this->actorBlog->id,
            'from_blog_username' => $this->actorBlog->username,
            'from_blog_avatar' => $this->actorBlog->avatar,
            'from_blog_avatar_shape' => $this->actorBlog->avatar_shape,
        ];
    }
}
