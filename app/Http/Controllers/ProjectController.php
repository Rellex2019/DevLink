<?php

namespace App\Http\Controllers;

use App\Events\InvitationAccepted;
use App\Events\InvitationRejected;
use App\Events\TeamInvited;
use App\Models\Invitation;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // Просмотр списка проектов с возможностью поиска по имени
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Фильтрация по access если нужно
        if ($request->has('access')) {
            $query->where('access', $request->access);
        }

        // Сортировка по умолчанию
        $query->orderBy('created_at', 'desc');

        return response()->json($query->get());
    }

    // Просмотр конкретного проекта по имени
    public function show($name)
    {
        $project = Project::where('name', $name)->firstOrFail();

        // Загружаем связанные данные если нужно
        $project->load(['teams', 'files', 'tasks', 'activities']);

        return response()->json($project);
    }
    public function showTasks($name)
    {
        $project = Project::where('name', $name)->firstOrFail();

        // Загружаем связанные данные если нужно
        $teams = $project->teams->load(['tasks']);

        return response()->json($teams);
    }

    // Создание нового проекта
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects',
            'description' => 'nullable|string',
            'access' => ['required', Rule::in(['private', 'public'])]
        ]);

        $project = Project::create([
            ...$validated,
            'owner_name' => $request->user()->name
        ]);

        return response()->json($project, 201);
    }

    // Обновление проекта по имени
    public function update(Request $request, $name)
    {
        $project = Project::where('name', $name)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'access' => ['sometimes', Rule::in(['private', 'public'])]
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy($name)
    {
        $project = Project::where('name', $name)->firstOrFail();
        $project->delete();

        return response()->json(null, 204);
    }

    // Дополнительный метод для поиска проектов (более гибкий) МБ УДАЛИТЬ
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'access' => 'nullable|in:private,public'
        ]);

        $query = Project::query();

        $searchTerms = explode(' ', $request->query);

        foreach ($searchTerms as $term) {
            $query->where('name', 'like', '%' . $term . '%');
        }

        if ($request->has('access')) {
            $query->where('access', $request->access);
        }

        return response()->json($query->get());
    }



    public function showInvites($projectName)
    {
        $project = Project::where('name', $projectName)->first();
        if (!$project) {
            return response()->json([
                'error' => 'Project not found',
                'message' => 'Проект ' . $projectName . ' не был найден'
            ], 404);
        }
        $invites = $project->invites->load('team');
        return response()->json($invites);
    }
    public function inviteTeam(Request $request, $projectName)
    {
        $request->validate([
            'team_ids' => 'required|array',
            'team_ids.*' => 'exists:teams,id'
        ]);

        $project = Project::where('name', $projectName)->first();
        if (!$project) {
            return response()->json([
                'error' => 'Project not found',
                'message' => 'Проект ' . $projectName . ' не был найден',

            ], 404);
        }

        $sender = auth()->user();
        $invitations = [];

        foreach ($request->team_ids as $teamId) {
            $exists = Invitation::where('project_id', $project->id)
                ->where('team_id', $teamId)
                ->exists();

            if (!$exists) {
                $invitation = Invitation::create([
                    'project_id' => $project->id,
                    'team_id' => $teamId,
                    'sender_id' => $sender->id,
                    'status' => 'pending'
                ]);

                broadcast(new TeamInvited($invitation));
                $invitations[] = $invitation;
            }
        }

        return response()->json([
            'message' => 'Приглашения отправлены',
            'invitations' => $invitations,
        ]);
    }
    public function deleteInviteTeam($projectName, $teamId)
    {
        $project = Project::where('name', $projectName)->first();
        if (!$project) {
            return response()->json([
                'error' => 'Project not found',
                'message' => 'Проект ' . $projectName . ' не был найден'
            ], 404);
        }
        $deleted = $project->invites()->where('team_id', $teamId)->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Приглашение для команды успешно удалено'
            ]);
        }

        return response()->json([
            'error' => 'Invitation not found',
            'message' => 'Приглашение для указанной команды не найдено'
        ], 404);
    }
    public function acceptInvitation(Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return response()->json(['message' => 'Приглашение уже обработано'], 400);
        }

        if (auth()->id() !== $invitation->team->owner) {
            return response()->json(['message' => 'Недостаточно прав'], 403);
        }
        DB::transaction(function () use ($invitation) {
            $invitation->project->teams()->attach($invitation->team_id);

            $invitation->delete();

            event(new InvitationAccepted($invitation));
        });

        return response()->json(['message' => 'Приглашение принято']);
    }

    public function rejectInvitation(Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return response()->json(['message' => 'Приглашение уже обработано'], 400);
        }

        if (auth()->id() !== $invitation->team->owner) {
            return response()->json(['message' => 'Недостаточно прав'], 403);
        }

        $invitation->delete();
        event(new InvitationRejected($invitation));

        return response()->json(['message' => 'Приглашение отклонено']);
    }
}
