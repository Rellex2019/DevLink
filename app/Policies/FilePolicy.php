<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function view(User $user)
    {
        // Разрешаем просмотр если пользователь владелец или проект публичный
        return true;
    }
    public function create(User $user)
    {
        return true; // Любой аутентифицированный пользователь может создавать проекты
    }
    public function update(User $user)
    {
        // Разрешаем изменение только владельцу
        return true;
    }

    public function delete(User $user)
    {
        // Разрешаем удаление только владельцу
        return true;
    }
}
