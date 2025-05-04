<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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

    // Удаление проекта по имени
    public function destroy($name)
    {
        $project = Project::where('name', $name)->firstOrFail();
        $project->delete();

        return response()->json(null, 204);
    }

    // Дополнительный метод для поиска проектов (более гибкий)
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'access' => 'nullable|in:private,public'
        ]);
        
        $query = Project::query();
        
        // Разбиваем поисковый запрос на отдельные слова
        $searchTerms = explode(' ', $request->query);
        
        foreach ($searchTerms as $term) {
            $query->where('name', 'like', '%' . $term . '%');
        }
        
        if ($request->has('access')) {
            $query->where('access', $request->access);
        }
        
        return response()->json($query->get());
    }
}