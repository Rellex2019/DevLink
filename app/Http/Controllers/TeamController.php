<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $newTeam = Team::create([
            ...$request->all(),
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
        $team->fill($validated);

        if ($request->hasFile('logo')) {
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
}
