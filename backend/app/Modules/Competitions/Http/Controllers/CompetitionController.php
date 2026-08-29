<?php

namespace App\Modules\Competitions\Http\Controllers;

use App\Modules\Competitions\Http\Controllers\Controller;
use App\Modules\Competitions\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        return response()->json(Competition::all());
    }

    public function show(Competition $competition)
    {
        return response()->json($competition);
    }

    public function store(Request $request)
    {
        $competition = Competition::create($request->validated());
        return response()->json($competition, 201);
    }

    public function update(Request $request, Competition $competition)
    {
        $competition->update($request->validated());
        return response()->json($competition);
    }

    public function destroy(Competition $competition)
    {
        $competition->delete();
        return response()->json(null, 204);
    }
}
