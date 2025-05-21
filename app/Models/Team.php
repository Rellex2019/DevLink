<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'owner',
        'name',
        'email',
        'logo',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function statuses()
    {
        return $this->hasMany(TaskStatus::class);
    }
}
