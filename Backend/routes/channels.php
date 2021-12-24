<?php

use App\Models\Blog;
use App\Models\ChatRoomGID;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// check if the user can use this channel
Broadcast::channel('channel-{id}', function ($user, $id) {
    // just for testing
    return true;

    $chatRooms = ChatRoomGID::where('id', $id)->first();

    // check if the current auth user is one of the users in any of these chatRooms
    $chatRoomOne = $chatRooms->chatRoomOne()->first();
    $chatRoomTwo = $chatRooms->chatRoomTwo()->first();

    $oneSender = $chatRoomOne->sender()->first()->id;
    $twoSender = $chatRoomTwo->sender()->first()->id;

    $curUserBlogsIDs = Blog::where('user_id', $user->id)->pluck('id')->toArray();

    foreach ($curUserBlogsIDs as $userBlogID) {
        if ($oneSender === $userBlogID || $twoSender === $curUserBlogsIDs) {
            return true;
        }
    }
    return false;
});
