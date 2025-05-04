<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_name',
        'access'
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_name', 'name');
    }
    
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function files()
    {
        return $this->hasMany(File::class)->whereNull('parent_id');
    }

    public function allFiles()
    {
        return $this->hasMany(File::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(RecentActivity::class);
    }
}
