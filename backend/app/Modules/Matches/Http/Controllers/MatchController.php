<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\Http\Controllers\Controller;
use App\Modules\Matches\Models\Match;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        return response()->json(Match::all());
    }

    public function show(Match $match)
    {
        return response()->json($match);
    }

    public function store(Request $request)
    {
        $match = Match::create($request->validated());
        return response()->json($match, 201);
    }

    public function update(Request $request, Match $match)
    {
        $match->update($request->validated());
        return response()->json($match);
    }

    public function destroy(Match $match)
    {
        $match->delete();
        return response()->json(null, 204);
    }
}
