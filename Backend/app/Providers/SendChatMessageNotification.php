<?php

namespace App\Providers;

use App\Events\ChatMessageEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatMessageNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ChatMessageEvent  $event
     * @return void
     */
    public function handle(ChatMessageEvent $event)
    {
        //
    }
}
