<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Http\Controllers\Controller;
use App\Modules\Events\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::all());
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $event = Event::create($request->validated());
        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->validated());
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }
}
