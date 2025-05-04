<?php

namespace App\Services;

use App\Models\File;
use App\Events\FileCreated;
use App\Events\FileDeleted;
use App\Events\FileUpdated;

class FileService
{
    public function getProjectFiles(int $projectId, ?int $parentId = null)
    {
        return File::where('project_id', $projectId)
            ->where('parent_id', $parentId)
            ->orderBy('type', 'desc') // Папки сначала
            ->orderBy('name')
            ->get();
    }

    public function createFolder(int $projectId, string $name, ?int $parentId = null): File
    {
        $folder = File::create([
            'project_id' => $projectId,
            'name' => $name,
            'type' => 'folder',
            'parent_id' => $parentId,
            'content' => null
        ]);

        event(new FileCreated($folder));

        return $folder;
    }

    public function createFile(int $projectId, array $data): File
    {
        $file = File::create([
            'project_id' => $projectId,
            'name' => $data['name'],
            'type' => 'file',
            'content' => $data['content'] ?? '',
            'parent_id' => $data['parent_id'] ?? null
        ]);

        event(new FileCreated($file));

        return $file;
    }

    public function updateFile(int $fileId, array $data): File
    {
        $file = File::findOrFail($fileId);

        $file->update([
            'name' => $data['name'] ?? $file->name,
            'content' => $data['content'] ?? $file->content
        ]);

        event(new FileUpdated($file));

        return $file;
    }

    public function updateFileContent(int $fileId, string $content): File
    {
        $file = File::findOrFail($fileId);

        $file->update(['content' => $content]);
        event(new FileUpdated($file));

        return $file;
    }

    public function deleteFile(int $fileId): void
    {
        $file = File::findOrFail($fileId);
        
        if ($file->type === 'folder') {
            $this->deleteFolderContents($file->project_id, $file->id);
        }
    
        $file->delete();
        event(new FileDeleted($file));
    }

    protected function deleteFolderContents(int $folderId): void
    {
        $folder = File::findOrFail($folderId);

        File::where('parent_id', $folderId)
            ->where('project_id', $folder->project_id)
            ->each(function ($file) {
                $this->deleteFile($file->id);
            });
    }
    public function moveFile(int $fileId, ?int $newParentId): File
    {
        $file = File::findOrFail($fileId);

        $file->update(['parent_id' => $newParentId]);
        event(new FileUpdated($file));

        return $file;
    }
}
