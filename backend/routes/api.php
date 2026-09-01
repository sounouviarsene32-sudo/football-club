<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'time' => now()->toDateTimeString(),
    ]);
});

/*
|--------------------------------------------------------------------------
| Football Teams Routes (Example - Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/football/teams', function () {
        return response()->json([
            ['id' => 1, 'name' => 'Paris Saint-Germain', 'city' => 'Paris', 'country' => 'France'],
            ['id' => 2, 'name' => 'Olympique de Marseille', 'city' => 'Marseille', 'country' => 'France'],
            ['id' => 3, 'name' => 'AS Monaco', 'city' => 'Monaco', 'country' => 'Monaco'],
            ['id' => 4, 'name' => 'Olympique Lyonnais', 'city' => 'Lyon', 'country' => 'France'],
        ]);
    });

    Route::get('/football/teams/{id}', function ($id) {
        $teams = [
            ['id' => 1, 'name' => 'Paris Saint-Germain', 'city' => 'Paris', 'country' => 'France', 'stadium' => 'Parc des Princes', 'founded' => 1970],
            ['id' => 2, 'name' => 'Olympique de Marseille', 'city' => 'Marseille', 'country' => 'France', 'stadium' => 'Stade Vélodrome', 'founded' => 1899],
            ['id' => 3, 'name' => 'AS Monaco', 'city' => 'Monaco', 'country' => 'Monaco', 'stadium' => 'Stade Louis II', 'founded' => 1924],
            ['id' => 4, 'name' => 'Olympique Lyonnais', 'city' => 'Lyon', 'country' => 'France', 'stadium' => 'Groupama Stadium', 'founded' => 1950],
        ];

        $team = collect($teams)->firstWhere('id', (int) $id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        return response()->json($team);
    });
});

