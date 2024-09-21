<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $allUser = User::all();
        return Inertia::render('Chat/Index', ['users' => $allUser]);
    }
    public function userData($id) {
       echo "check";
    }
}
