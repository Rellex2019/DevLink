<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'bio',
        'position',
        'website',
        'location',
        'github',
        'twitter', //Удалить
        'linkedin',
        'dark_mode',
        'theme_color',
        'total_projects',
        'completed_tasks'
    ];
    protected $appends = ['name'];
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }
}
