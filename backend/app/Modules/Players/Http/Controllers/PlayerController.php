<?php

namespace App\Modules\Players\Http\Controllers;

use App\Modules\Players\Http\Controllers\Controller;
use App\Modules\Players\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        return response()->json(Player::all());
    }

    public function show(Player $player)
    {
        return response()->json($player);
    }

    public function store(Request $request)
    {
        $player = Player::create($request->validated());
        return response()->json($player, 201);
    }

    public function update(Request $request, Player $player)
    {
        $player->update($request->validated());
        return response()->json($player);
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return response()->json(null, 204);
    }
}
