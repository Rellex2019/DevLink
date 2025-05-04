<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Models\Project;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index( string $username, string $projectName, ?int $parentId = null): JsonResponse
    {
        $project = Project::with('owner')
            ->whereHas('owner', function ($query) use ($username) {
                $query->where('name', $username); // или 'username' в зависимости от структуры БД
            })
            ->where('name', $projectName)
            ->first();
        $files = $this->fileService->getProjectFiles($project->id, $parentId);
        return response()->json([
            'files'=>$files,
            'project_id' => $project->id
        ]);
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

    public function updateContent(int $fileId, Request $request)
    {
        $request->validate(['content' => 'required|string']);

        $file = $this->fileService->updateFileContent($fileId, $request->content);

        return response()->json($file);
    }
    public function destroy(int $fileId): JsonResponse
    {
        $this->fileService->deleteFile($fileId);
        return response()->json(null, 204);
    }
}
