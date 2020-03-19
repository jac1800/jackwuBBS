<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
//    public function update(User $user, Reply $reply)
//    {
//        // return $reply->user_id == $user->id;
//        return true;
//    }

    public function destroy(User $user, Reply $reply)
    {
       //只有评论者和帖子的作者可以删除评论
        return $user->isAuthorof($reply) || $user->isAuthorof($reply->topic);
    }
}
