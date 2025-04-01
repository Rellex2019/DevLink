<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }
}
