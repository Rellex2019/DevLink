<?php

namespace App\Http\Controllers;

use App\Events\UserInvited;
use App\Models\InviteTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }
    public function show($teamName)
    {
        $data = [];
        $team = Team::where('name', $teamName)->first();
        $members = $team->users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->pivot->role, // Получаем роль из pivot
                'profile' => $user->profile // Загружаем профиль
            ];
        });
        $projects = $team->projects;
        $isMember = false;
        $isOwner = false;
        if (auth()->check()) {
            $isMember = $team->users->contains(auth()->id());
            $isOwner = $team->users->where('id', auth()->id())->where('pivot.role', 'owner')->isNotEmpty();
        }
        $team->setHidden(['users', 'projects']);

        $data = [
            'team' => $team,
            'members' => $members,
            'projects' => $projects,
            'isMember' => $isMember,
            'isOwner' => $isOwner
        ];

        return response()->json($data);
    }
    public function invitedPeople(Request $request, $teamId)
    {
        $team = Team::find($teamId);
        $user = $request->user();
        $invites = $team->invitedPeople->load(['user.profile', 'team']);
        return response()->json($invites);
    }
    public function sendInvite($teamId, $userId)
    {
        $team = Team::find($teamId);
        $sender = auth()->user();
        if (!$team) {
            return response()->json([
                'error' => 'Team not found',
                'message' => 'Команда не была найдена',

            ], 404);
        }
        $exists = InviteTeam::where('team_id', $teamId)
            ->where('user_id', $userId)->exists()
            || DB::table('team_user')->where('team_id', $teamId)
            ->where('user_id', $userId)->exists();

        if (!$exists) {
            $inviteTeam = InviteTeam::create([
                'user_id' => $userId,
                'team_id' => $teamId,
                'sender_id' => $sender->id,
            ]);
            broadcast(new UserInvited($inviteTeam));
        }
        return response()->json([
            'message' => 'Приглашение отправлено',
        ]);
    }
    public function deleteInviteUser(InviteTeam $invite)
    {
        $deleted = $invite->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Приглашение для пользователя успешно удалено'
            ]);
        }

        return response()->json([
            'error' => 'Invitation not found',
            'message' => 'Приглашение для указаннаго пользователя не найдено'
        ], 404);
    }


    public function invites($teamId)
    {
        $team = Team::find($teamId);
        $invites = $team->invites->where('status', 'pending')->load(['project', 'sender']);
        return response()->json($invites);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $teams = Team::where('name', 'like', '%' . $query . '%')
            ->withCount('users')
            ->limit(10)
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'logo' => $team->logo,
                    'members_count' => $team->users_count
                ];
            });

        return response()->json($teams);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'email' => 'required|email|unique:teams',
            'logo' => 'nullable|string'
        ]);
        $newTeam = Team::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'logo' => $validated['logo'] ?? '/storage/logos/default-logo.jpg',
            'owner' => $request->user()->id
        ]);
        $newTeam->users()->attach($request->user()->id, [
            'role' => 'owner'
        ]);
        return response()->json($newTeam);
    }
    public function update(Request $request, $teamId)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|max:4000',
        ]);

        $team = Team::findOrFail($teamId);
        $team->fill(
            [
                'name' => $validated['name'] ?? $team->name,
                'email' => $validated['email'] ?? $team->email
            ]
        );

        if ($request->hasFile('logo') && $request->logo != null) {
            $rawLogo = $team->getRawOriginal('logo');
            if ($team->logo) {

                $oldLogoPath = str_replace('/storage/', '', $rawLogo);
                if (!str_contains($oldLogoPath, 'default-logo') && Storage::disk('public')->exists($oldLogoPath)) {
                    Storage::disk('public')->delete($oldLogoPath);
                }
            }
            $path = $request->file('logo')->store('logos', 'public');
            $team->logo = '/storage/' . $path;
        }

        $team->save();
        return response()->json($team);
    }
    public function destroy($teamId)
    {
        $team = Team::find($teamId);
        $team->delete();
        return response()->json($team);
    }
}
