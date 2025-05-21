<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function show($projectName, $userName, $teamName)
    {
        $project = Project::with(['teams', 'teams.tasks', 'teams.statuses'])
            ->where('name', $projectName)
            ->where('owner_name', $userName)
            ->firstOrFail();

        $team = $project->teams->where('name', $teamName)->first();

        if (!$team) {
            return response()->json(['message' => 'Команда не найдена'], 404);
        }

        return response()->json([
            'statuses' => $team->statuses->makeVisible(['id', 'name', 'color']),
            'tasks' => $team->tasks->where('project_id', $project->id),
            'project_id' => $project->id,
            'team_id' => $team->id
        ]);
    }

    public function storeStatus(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7'
        ]);

        try {
            DB::beginTransaction();

            $status = TaskStatus::create($validated);

            DB::commit();

            return response()->json($status, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка при создании статуса',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Обновление статуса
    public function updateStatus(Request $request, $projectName, $userName, $teamName, $statusId)
    {
        $project = Project::where('name', $projectName)
            ->where('owner_name', $userName)
            ->firstOrFail();
        
        $team = $project->teams()->where('name', $teamName)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:7'
        ]);
    
        $status = TaskStatus::where('id', $statusId)
            ->where('team_id', $team->id)
            ->firstOrFail();
    
        try {
            DB::beginTransaction();
            
            $status->update($validated);
            
            DB::commit();
            
            return response()->json($status);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка при обновлении статуса',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Удаление статуса
    public function destroyStatus($projectNama, $userName, $teamName,$statusId)
    {
        $status = TaskStatus::findOrFail($statusId);

        try {
            DB::beginTransaction();

            if ($status->tasks()->count()>0) {
                return response()->json([
                    'message' => 'Нельзя удали статус с задачами',
                ], 422);
            }

            $status->delete();

            DB::commit();

            return response()->json( [],204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка при удалении статуса',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Создание новой задачи
    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'required|exists:teams,id',
            'status' => 'required|exists:task_statuses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            $task = Task::create(array_merge($validated, [
                'created_by' => auth()->id()
            ]));

            DB::commit();

            return response()->json($task, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка в создании задания',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Обновление задачи
    public function updateTask(Request $request,$projectNama, $userName, $teamName, $taskId)
    {
        $validated = $request->validate([
            'status' => 'sometimes|exists:task_statuses,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        $task = Task::findOrFail($taskId);

        try {
            DB::beginTransaction();

            $task->update(array_merge($validated, [
                'updated_at' => now()
            ]));

            DB::commit();

            return response()->json($task);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка с создании задания',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Удаление задачи
    public function destroyTask( $projectNama, $userName, $teamName, $taskId)
    {
        $task = Task::findOrFail($taskId);

        try {
            DB::beginTransaction();

            $task->delete();

            DB::commit();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ошибка при удалении задания',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
