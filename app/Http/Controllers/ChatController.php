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
        $userMessage = null;
        $allUser = null;
        $chatUser = null;
        $frinds = Friendship::with('friends')->where('user_id', Auth::user()->id)->get();
        $allUser = User::all();
        if($id){
            $userMessage = Message::where('sender_id', $id)->get();
        }
        return Inertia::render('Chat/Index', ['users' => $allUser, 'message' => $userMessage, 'frinds' => $frinds]);
    }

    public function storeMessage(Request $request, $id){
        $store = Message::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $id,
            'content' => $request->message,
            'content_type' => 'test',
        ]);
        return back();
    }
    public function addChat($id){
        Friendship::firstOrCreate([
            'user_id' => Auth::user()->id,
            'friend_id' => $id,
        ]);
        return back();
    }

}
