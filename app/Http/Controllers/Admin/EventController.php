<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;

        $this->middleware('user.can.edit.event')->only(['edit', 'update']);
    }

    public function index()
    {
        $events = auth()->user()->events()->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(EventRequest $request)
    {
        $event = $request->all();

        if ($banner = $request->file('banner')) {
            $event['banner'] = $banner->store('banner', 'public');
        }

        $event = $this->event->create($event);
        $event->owner()->associate(auth()->user());
        $event->save();

        return redirect()->route('admin.events.index');
    }

    public function edit($event)
    {
        $event = $this->event->findOrFail($event);
        return view('admin.events.edit', compact('event'));
    }

    public function update($event, EventRequest $request)
    {
        $event = $this->event->findOrFail($event);
        $eventData = $request->all();

        if ($banner = $request->file('banner')) {
            if (Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }
            $eventData['banner'] = $banner->store('banner', 'public');
        }


        $event->update($eventData);

        return redirect()->back();
    }

    public function destroy($event)
    {
        $event = $this->event->findOrFail($event);
        $event->delete();

        return redirect()->route('admin.events.index');
    }
}
