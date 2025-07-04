<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }


    public function index(Request $request, string $username, string $projectName, ?int $parentId = null): JsonResponse
{
    $projectCacheKey = "project:{$username}:{$projectName}";
    $filesCacheKey = "files:{$projectName}:{$parentId}";

    try {
        $project = $this->getCachedProject($projectCacheKey, $username, $projectName);
        
        $files = $this->getCachedFiles($filesCacheKey, $project, $parentId);

        $isOwner = $request->user()?->id === $this->resolveProjectOwnerId($project);
        $isMember = $isOwner || $this->checkProjectMembership($request->user(), $project);

        return response()->json([
            'files' => $files,
            'project_id' => $this->resolveProjectId($project),
            'isOwner' => $isOwner,
            'isMember' => $isMember
        ], 200, [], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        Log::error("ProjectController error: " . $e->getMessage());
        return response()->json(['error' => 'Server error'], 500);
    }
}

protected function getCachedProject(string $key, string $username, string $projectName)
{
    $project = Cache::get($key);

    if (!$project) {
        return Cache::lock("lock:{$key}", 10)->block(5, function () use ($key, $username, $projectName) {
            return Cache::remember($key, now()->addHour(), function () use ($username, $projectName) {
                $project = Project::with(['owner:id,name', 'teams.users:id'])
                    ->whereHas('owner', fn($q) => $q->where('name', $username))
                    ->where('name', $projectName)
                    ->firstOrFail();

                return $project->toArray(); 
            });
        });
    }

    return is_array($project) ? $project : unserialize($project);
}

protected function getCachedFiles(string $key, $project, ?int $parentId)
{
    return Cache::remember($key, now()->addSeconds(15), function () use ($project, $parentId) {
        return $this->fileService->getProjectFiles(
            $this->resolveProjectId($project), 
            $parentId
        );
    });
}

protected function resolveProjectId($project): int
{
    return is_array($project) ? $project['id'] : $project->id;
}

protected function resolveProjectOwnerId($project): int
{
    return is_array($project) ? $project['owner']['id'] : $project->owner->id;
}

protected function checkProjectMembership(?User $user, $project): bool
{
    if (!$user) return false;

    if (is_array($project)) {
        return collect($project['teams'] ?? [])
            ->pluck('users')
            ->collapse()
            ->contains('id', $user->id);
    }

    return $project->teams->pluck('users')->collapse()->contains('id', $user->id);
}


    public function storeFolder(StoreFolderRequest $request, int $projectId): JsonResponse
    {
        $folder = $this->fileService->createFolder(
            $projectId,
            $request->name,
            $request->parent_id
        );

        return response()->json($folder, 201);
    }

    public function store(StoreFileRequest $request, int $projectId)
    {
        $file = $this->fileService->createFile($projectId, $request->validated());

        return response()->json($file, 201);
    }
    public function updateObject(Request $request, int $projectId, int $fileId)
    {
        $request->validate(['name' => 'required|string']);

        $file = $this->fileService->updateFile($fileId, $request->all());
        return response()->json($file);
    }
    public function updateContent(int $projectId, int $fileId, Request $request)
    {
        $request->validate(['content' => 'nullable|string']);

        $file = $this->fileService->updateFileContent($fileId, $request->content);

        return response()->json($file);
    }
    public function moveObject(int $projectId, int $objectId, Request $request)
    {
        $request->validate(['parent_id' => 'required|int']);

        $file = $this->fileService->moveFile($objectId, $request->parent_id);

        return response()->json($file);
    }


    public function destroy(int $projectId, int $fileId): JsonResponse
    {
        $this->fileService->deleteFile($fileId);
        return response()->json(null, 204);
    }
}
