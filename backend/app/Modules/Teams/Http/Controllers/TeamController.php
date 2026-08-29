<?php

namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Http\Controllers\Controller;
use App\Modules\Teams\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all());
    }

    public function show(Team $team)
    {
        return response()->json($team);
    }

    public function store(Request $request)
    {
        $team = Team::create($request->validated());
        return response()->json($team, 201);
    }

    public function update(Request $request, Team $team)
    {
        $team->update($request->validated());
        return response()->json($team);
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(null, 204);
    }
}
