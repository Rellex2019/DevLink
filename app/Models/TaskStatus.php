<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $table = 'task_statuses';
    protected $fillable = ['project_id', 'team_id', 'name', 'color'];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status');
    }
}