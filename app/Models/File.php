<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'type',
        'content',
        'parent_id'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    public function history()
    {
        return $this->hasMany(FileHistory::class)->orderByDesc('created_at');
    }
}
