<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($id = null)
    {
        $userId = Auth::user()->id;
        $frinds = Friendship::where('user_id', $userId)->orWhere('friend_id', $userId)->get();
        $allFriends = $frinds->map(function ($friend) use ($userId) {

            $friendId = ($friend->user_id == $userId) ? $friend->friend_id : $friend->user_id;

            $user = User::find($friendId);
            $messages = $this->checkingId(Message::class, 'sender_id', $userId, 'receiver_id', $friendId, 'latest');

            return [
                'id' => $friend->id,
                // 'user_id' => $friend->user_id,
                'friend_id' => $friend->friend_id,
                'status' => $friend->status,
                'created_at' => $friend->created_at,
                'messages' => $messages,
                'user' => $user,
            ];
        });

        $messages = null;
        if ($id) {
            $chat_user_name = User::find($id);
            $messages = $this->checkingId(Message::class, 'sender_id', $userId, 'receiver_id', $id, 'get');
        }
        // return $allFriends;
        return Inertia::render('Chat/Index', ['chat_user_name' => $chat_user_name, 'messageData' => $messages, 'allFriends' => $allFriends,  'chatUserId' => $id,]);
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
    public function allUsers()
    {
        $allUser = User::all();
        return response()->json(['allUsers' => $allUser]);
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
