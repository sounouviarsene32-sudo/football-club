<?php

namespace App\Modules\Trainings\Http\Controllers;

use App\Modules\Trainings\Http\Controllers\Controller;
use App\Modules\Trainings\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        return response()->json(Training::all());
    }

    public function show(Training $training)
    {
        return response()->json($training);
    }

    public function store(Request $request)
    {
        $training = Training::create($request->validated());
        return response()->json($training, 201);
    }

    public function update(Request $request, Training $training)
    {
        $training->update($request->validated());
        return response()->json($training);
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return response()->json(null, 204);
    }
}
