<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected static function booted()
    {
        static::created(function ($user) {
            $user->profile()->create([
                'avatar' => '/avatars/default-avatar.svg' 
            ]);
        });
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_name', 'name');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('role');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function fileHistories()
    {
        return $this->hasMany(FileHistory::class);
    }

    public function activities()
    {
        return $this->hasMany(RecentActivity::class);
    }

    public function invites()
    {
        return $this->hasMany(InviteTeam::class);
    }
}
