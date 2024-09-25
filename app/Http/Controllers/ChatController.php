<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Message;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($id = null)
    {
        $userId = Auth::user()->id;
        $frinds = Friendship::where('user_id', $userId)->orWhere('friend_id', $userId)->get();
        $allFriends = $frinds->map(function ($friend) use ($userId) {

            $friendId = ($friend->user_id == $userId) ? $friend->friend_id : $friend->user_id;

            // user Data
            $user = User::find($friendId);
            // Message Data Latest
            $messages = $this->checkingId(Message::class, 'sender_id', $userId, 'receiver_id', $friendId, 'latest');
            // Count Unread all message

            $unreadMessage = Message::where('receiver_id', $userId)->where('sender_id', $friendId)->where('is_read', false)->count();
            return [
                'id' => $friend->id,
                'friend_id' => $friend->friend_id,
                'user_id' => $friend->user_id,
                'status' => $friend->status,
                'unreadMessage' => $unreadMessage,
                'created_at' => $friend->created_at,
                'messages' => $messages,
                'user' => $user,
            ];
        });

        $messages = null;
        $chat_user_name = null;
        $invite = null;
        if ($id) {
            $invite = $this->checkingId(Friendship::class, 'user_id', $userId, 'friend_id', $id, 'first');
            $chat_user_name = User::find($id);
            $messages = $this->checkingId(Message::class, 'sender_id', $userId, 'receiver_id', $id, 'get');
        }
        // return $invite;
        return Inertia::render('Chat/Index', [
            'userStatus' => $invite,
            'chat_user_name' => $chat_user_name,
            'messageData' => $messages,
            'allFriends' => $allFriends,
            'chatUserId' => $id,
        ]);
    }

    public function storeMessage(Request $request, $chatUserId)
    {
        $userId = Auth::user()->id;
        $request->validate([
            'message' => 'required',
        ]);

        $checkUser = $this->checkingId(Friendship::class, 'user_id', $userId, 'friend_id', $chatUserId, 'firstOrFail');
        $checkMessage = $this->checkingId(Message::class, 'sender_id', $chatUserId, 'receiver_id', $userId, 'get');

        $content = $request->message;
        $attachments = $request->attachments;
        $countMessage = $checkMessage->count();

        switch ($checkUser->status) {
            case 'blocked':
                return 'You are blocked';

            case 'pending':
                if ($countMessage < 2) {
                    $storeDataFN = $this->storeData($chatUserId, $content, $attachments);
                    if ($storeDataFN) {
                        return back();
                    } else {
                        return $storeDataFN;
                    }
                } else {
                    return "Your invitation is not accepted by the user";
                }

            case 'accepted':
                $storeData = $this->storeData($chatUserId, $content, $attachments);
                return back()->with(['message', 'success sent message']);

            default:
                return 'wrong user';
        }
    }

    public function invite($id)
    {
        $userId = Auth::user()->id;
        $alreadyAdded = $this->checkingId(Friendship::class, 'user_id', $userId, 'friend_id', $id, 'exists');

        if (!$alreadyAdded) {
            Friendship::create([
                'user_id' => Auth::user()->id,
                'friend_id' => $id,
            ]);
            return back()->with('success', 'Friend added successfully!');
        }

        return back()->with('error', 'Friend already added!');
    }

    public function inviteStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $query = Friendship::where('id', $id);
        if ($request->status == 'delete') {
            $query->delete();
            return Inertia::setRootView('Chat/Index');
        } else {
            $query->update(['status' => $request->status]);
            return  back()->with(['message' => 'User ' . $request->status . ' Successful', 'status' => 'success']);
        }
    }

    public function allUsers()
    {
        $allUser = User::all();
        return response()->json(['allUsers' => $allUser]);
    }

    public function messageDelete($id)
    {
        $deleteMessage = Message::find($id)->delete();
        return back()->with(['message' => $deleteMessage]);
    }

    public function messageUpdate(Request $request, $id)
    {
        $request->validate([
            'updateContent' => 'required|string',
        ]);
        $updateMessage = Message::where('id', $id)->update([
            'content' => $request->updateContent,
        ]);
        return back()->with(['message' => $updateMessage]);
    }

    public function check($id)
    {
        $userId = Auth::user()->id;
        $alreadyAdded = Friendship::where(function ($query) use ($id, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $id);
        })
            ->orWhere(function ($query) use ($userId, $id) {
                $query->where('friend_id', $userId)->where('user_id', $id);
            })->exists();
        return $alreadyAdded;
    }


    private function storeData($chatUserId,  $content, $attachments)
    {
        try {
            $storeMessage = Message::create([
                'sender_id' => Auth::user()->id,
                'receiver_id' => $chatUserId,
                'content' => $content,
                'attachments' => $attachments,
                'content_type' => 'text',
            ]);
            return $storeMessage;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    private function checkingId($model, $firstCol, $firstVal, $secondCol, $secondVal, $type = "get")
    {
        $query = $model::where(function ($query) use ($firstCol, $firstVal, $secondCol, $secondVal) {
            $query->where($firstCol, $firstVal)->where($secondCol, $secondVal);
        })->orWhere(function ($query) use ($firstCol, $firstVal, $secondCol, $secondVal) {
            $query->where($secondCol, $firstVal)->where($firstCol, $secondVal);
        });


        return match ($type) {
            'first' => $query->first(),
            'firstOrFail' => $query->firstOrFail(),
            'count' => $query->count(),
            'exists' => $query->exists(),
            'latest' => $query->latest('created_at')->first(),
            default => $query->get(),
        };
    }
}
