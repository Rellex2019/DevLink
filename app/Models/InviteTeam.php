<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteTeam extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'sender_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
