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
            $otherData = $this->otherData($friendId, $userId);
            return [
                'id' => $friend->id,
                'user_id' => $friend->user_id,
                'friend_id' => $friend->friend_id,
                'status' => $friend->status,
                'created_at' => $friend->created_at,
                'messages' => $otherData['message'],
                'user' => $otherData['user'],
            ];
        });
        // return $allFriends;


        // $friends = Friendship::where(function ($query) use ($userId) {
        //     $query->where('user_id', $userId);
        // })
        //     ->orWhere(function ($query) use ($userId) {
        //         $query->where('friend_id', $userId);
        //     })
        //     ->get();

        // $friendData = $friends->map(function ($friend) use ($userId) {
        //     if ($friend->user_id == $userId) {
        //         return $friend->received_requests;
        //     } else {
        //         return $friend->sent_requests;
        //     }
        // });




        $userMessage = null;
        if ($id) {
            $messages = Message::where(function ($query) use ($id, $userId) {
                $query->where('sender_id', $userId)->where('receiver_id', $id);
            })->orWhere(function ($query) use ($id, $userId) {
                $query->where('sender_id', $id)->where('receiver_id', $userId);
            })->get();
        }
        // return $messages;
        return Inertia::render('Chat/Index', ['messageData' => $messages, 'allFriends' => $allFriends]);
    }

    private function otherData($friendId, $userId)
    {
        $user = User::find($friendId);
        $message = Message::where(function ($query) use ($friendId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $friendId);
        })->orWhere(function ($query) use ($friendId, $userId) {
            $query->where('sender_id', $friendId)->where('receiver_id', $userId);
        })->get();
        return ['message' => $message, 'user' => $user];
    }

    public function storeMessage(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $checkUser = Friendship::findOrFail($id);
        // return $checkUser;
        if ($checkUser) {
            $checkMessage = Message::where(function ($query) use ($checkUser) {
                $query->where('sender_id', $checkUser->user_id)
                    ->where('receiver_id', $checkUser->friend_id);
            })->orWhere(function ($query) use ($checkUser) {
                $query->where('receiver_id', $checkUser->user_id)
                    ->where('sender_id', $checkUser->friend_id);
            })->get();
            $content = $request->message;
            $attachments = $request->attachments;
            $countMessage = $checkMessage->count();
            // return $countMessage;

            if ($checkUser->status == 'blocked') {
                return 'You are blocked';
            } else if ($checkUser->status == 'pending') {
                if ($countMessage < 2) {
                    $storeDataFN = $this->storeData($checkUser->friend_id, $checkUser->user_id, $content, $attachments);
                    if ($storeDataFN) {
                        // return $storeDataFN;
                        return back();
                    } else {
                        return $storeDataFN;
                    };
                } else {
                    return "Your invitaion Not accepted the user";
                }
            } else {
                $storeData = $this->storeData($checkUser->friend_id, $checkUser->user_id, $content, $attachments);
                // return $storeData;
                return back()->with(['message', 'success sent message']);
            }
        }
        return "wrong User";
    }
    private function storeData($friend_id, $user_id,  $content, $attachments)
    {
        $userId = Auth::user()->id == $user_id ? $friend_id : $user_id;
        try {
            $storeMessage = Message::create([
                'sender_id' => Auth::user()->id,
                'receiver_id' => $userId,
                'content' => $content,
                'attachments' => $attachments,
                'content_type' => 'text',
            ]);
            return $storeMessage;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function addChat($id)
    {
        $userId = Auth::user()->id;
        $alreadyAdded = Friendship::where(function ($query) use ($id, $userId) {
            $query->where('user_id', $userId)->where('friend_id', $id);
        })
            ->orWhere(function ($query) use ($userId, $id) {
                $query->where('friend_id', $userId)->where('user_id', $id);
            })->exists();

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
}
