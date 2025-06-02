<?php

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('user.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('team.{ownerId}', function (User $user, $ownerId) {
    // Проверяем, является ли пользователь владельцем команды
    return $user->teams()->where('owner', $user->id)
        ->wherePivot('role', 'owner')
        ->exists();
});

Broadcast::channel('project.{projectId}', function (User $user, $projectId) {
    // Проверяем, имеет ли пользователь доступ к проекту
    return $user->projects()->where('projects.id', $projectId)->exists();
});