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
        $friends = Friendship::where(function ($query) use ($userId) {
            // Check if the current user is the sender (user_id)
            $query->where('user_id', $userId);
        })
            ->orWhere(function ($query) use ($userId) {
                // Check if the current user is the receiver (friend_id)
                $query->where('friend_id', $userId);
            })
            ->get();

        // Collect user data based on the friendship
        $friendData = $friends->map(function ($friend) use ($userId) {
            if ($friend->user_id == $userId) {
                // If current user is the sender, retrieve the friend's data from the received_requests
                return $friend->received_requests;
            } else {
                // If current user is the receiver, retrieve the sender's data from the sent_requests
                return $friend->sent_requests;
            }
        });
        // return $friends;



        $userMessage = null;
        //    $frinds = Friendship::with('friends')->where('user_id', $userId)->orWhere('friend_id', $userId)->get();
        //    return $frinds;
        if ($id) {
            $checkUser = Friendship::findOrFail($id);

            $userMessage = Message::where(function ($query) use ($checkUser) {
                // First condition: user is the sender, friend is the receiver
                $query->where('sender_id', $checkUser->user_id)
                    ->where('receiver_id', $checkUser->friend_id);
            })->orWhere(function ($query) use ($checkUser) {
                // Second condition: user is the receiver, friend is the sender
                $query->where('receiver_id', $checkUser->user_id)
                    ->where('sender_id', $checkUser->friend_id);
            })->get();
            // // $userMessage = Message::whereRaw('sender_id = ' . Auth::user()->id . ' || receiver_id = ' . Auth::user()->id )->get();
            // $userMessage = Message::where(function ($query) use ($userId) {
            //     $query->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            // })
            //     ->where(function ($query) use ($id) {
            //         $query->where('sender_id', $id)->orWhere('receiver_id', $id);
            //     })->get();
        }
        // return $userMessage;
        return Inertia::render('Chat/Index', ['messageData' => $userMessage, 'friends' => $friends]);
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
