<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'friend_id',
    ];

    public function received_requests()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    public function sent_requests()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function message($id){
    //     $userId  = Auth::user()->id;
    //     $message = Message::where(function($query)use($id, $userId){
    //         $query->where('sender_id', $userId)->where('receiver_id' , $id);
    //     })->orWhere(function($query)use($id, $userId){
    //         $query->where('sender_id', $id)->where('receiver_id' , $userId);
    //     })->get();
    //     return $this->message;
    // }
}
