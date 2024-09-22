<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'friend_id',
    ];

    public function friends(){
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
}
