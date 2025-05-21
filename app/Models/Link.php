<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';
    protected $fillable = ['name', 'url'];

    public function userProfiles()
    {
        return $this->belongsToMany(UserProfile::class);
    }
}
