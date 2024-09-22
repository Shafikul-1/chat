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

        $userMessage = null;
        $frinds = Friendship::with('friends')->where('user_id', $userId)->orWhere('friend_id', $userId)->get();
        // return $frinds;
        if ($id) {
            // $userMessage = Message::whereRaw('sender_id = ' . Auth::user()->id . ' || receiver_id = ' . Auth::user()->id )->get();
            $userMessage = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })
                ->where(function ($query) use ($id) {
                    $query->where('sender_id', $id)->orWhere('receiver_id', $id);
                })->get();
        }
        return Inertia::render('Chat/Index', ['messageData' => $userMessage, 'frinds' => $frinds]);
    }

    public function storeMessage(Request $request, $id)
    {
        $userId = Auth::user()->id;
        $checkUser = Friendship::where('user_id', $userId)->where('friend_id', $id)->firstOrFail();
        if ($checkUser) {
            $checkMessage = Message::where('sender_id', $userId)->where('receiver_id', $id)->get();
            $content = $request->message;
            $attachments = $request->attachments;

            if ($checkUser->status == 'blocked') {
                return response()->json(['message' => 'You are blocked'], 403);
            } else if ($checkUser->status == 'pending') {
                $countMessage = count($checkMessage);
                if ($countMessage < 2) {
                    $storeDataFN = $this->storeData($id, $content, $attachments);
                    if ($storeDataFN) {

                        return back();
                    } else {
                        return $storeDataFN;
                    };
                } else {
                    return back();
                }
            } else {
                $this->storeData($id, $content, $attachments);
                return back()->with(['message', 'success sent message']);
            }
        }
        return "wrong User";
    }
    private function storeData($receiverId, $content, $attachments)
    {
        try {
            Message::create([
                'sender_id' => Auth::user()->id,
                'receiver_id' => $receiverId,
                'content' => $content,
                'attachments' => $attachments,
                'content_type' => 'text',
            ]);
            return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function addChat($id)
    {
        Friendship::firstOrCreate([
            'user_id' => Auth::user()->id,
            'friend_id' => $id,
        ]);
        return back();
    }
    public function allUsers()
    {
        $allUser = User::all();
        return response()->json(['allUsers' => $allUser]);
    }
}
