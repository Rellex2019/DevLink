<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    const MAX_LINKS = 4;
    public function show($name, Request $request)
    {
        $user = User::where('name', $name)->firstOrFail();
        $user->load(['profile']);
        $user->profile->makeHidden('user');
        $currentUserId = $request->header('X-Current-User-Id');
        $isOwner = !is_null($currentUserId) && $user->id == $currentUserId;

        $user->setRelation('projects', $user->projects()
            ->where(function ($query) use ($isOwner) {
                $query->where('access', 'public');
                if ($isOwner) {
                    $query->orWhere('access', 'private');
                }
            })
            ->get());

        $user->profile->load(['links' => function ($query) {
            $query->select('links.id', 'links.name', 'links.url as link');
        }]);
        $user->load(['teams']);
        $user->isOwner = $isOwner;

        return response()->json($user);
    }


    public function update(Request $request, $username)
    {
        $user = User::where('name', $username)->firstOrFail();
        if (Auth::id() !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:4000',
            'links' => 'nullable|array|max:' . self::MAX_LINKS,
            'links.*.name' => 'required_with:links|string|max:50',
            'links.*.url' => 'required_with:links|url|max:255',
        ]);


        $profile = $user->profile;
        $profile->fill($validated);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar && !str_contains($profile->avatar, 'default-avatar')) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = '/storage' . '/' . $path;
        }

        $profile->save();
        if ($request->has('links')) {
            if (empty($request->links)) {
                $profile->links()->detach();
                return;
            }

            $linksToSync = [];

            foreach ($request->links as $linkData) {
                // Поиск существующей ссылки по URL
                $link = Link::where('url', $linkData['url'])->first();
        
                if ($link) {
                    // Если ссылка существует, обновляем её название
                    $link->update(['name' => $linkData['name']]);
                    $linksToSync[] = $link->id;
                } else {
                    // Если ссылки нет, создаём новую
                    $link = Link::create([
                        'url' => $linkData['url'],
                        'name' => $linkData['name']
                    ]);
                    $linksToSync[] = $link->id;
                }
            }
            $profile->links()->sync($linksToSync);
            $this->cleanupUnusedLinks($linksToSync);
        }
        return response()->json([
            'message' => 'Профиль успешно обновлен',
            'profile' => $profile->fresh()->load(['links' => function ($query) {
                $query->select('links.id', 'name', 'url as link');
            }])
        ]);
    }





    public function index()
    {
        $userProfile = Auth::user()->profile;

        return response()->json([
            'links' => $userProfile->links()->select('id', 'name', 'url')->get()
        ]);
    }



    /**
     * Удалить неиспользуемые ссылки
     */
    private function cleanupUnusedLinks(array $usedLinkIds)
    {
        // Находим ссылки, которые больше никто не использует
        $unusedLinks = Link::whereNotIn('id', $usedLinkIds)
            ->doesntHave('userProfiles')
            ->get();

        // Удаляем их
        foreach ($unusedLinks as $link) {
            $link->delete();
        }
    }
}
