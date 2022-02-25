<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\EventUser;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private $userLogged;

    public function __construct()
    {
        $this->setUserLogged();
    }

    private function setUserLogged()
    {
        $this->middleware(function ($request, $next) {
            $this->userLogged = Auth::user();
 
            return $next($request);
        });
    }

    private function getUserLogged()
    {
        return $this->userLogged;
    }

    public function index()
    {
        $search = Request('search');

        if ($search) {
            $events = Event::where([
                [
                    'title', 
                    'like', 
                    '%'.$search.'%'
                ]
            ])
            ->get();
        }else {
            $events = Event::all();
        }

        return view('welcome', [
            'events' => $events,
            'search' => $search
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function show($id)
    {
        $user = $this->getUserLogged();

        $hasUserJoined = false;

        if ($user) {
            $hasUserJoined = EventUser::where([
                ['user_id', $user->id],
                ['event_id', $id]
            ])
            ->exists();
        }
    
        $event = Event::findOrFail($id);

        $owner = User::where('id', $event->user_id)
        ->first()
        ->toArray();

        return view('events.show', [
            'event' => $event, 
            'owner' => $owner,
            'hasUserJoined' => $hasUserJoined
        ]);
    }
    
    public function store(Request $request)
    {
        $event = new Event;

        $event->title = $request->title;
        $event->description = $request->description;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->items = $request->items;
        $event->date = $request->date;
        $event->user_id = auth()->user()->id;

        // image upload 
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $ext = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now') . '.' . $ext);

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }

        $event->save();

        return redirect('/')->with('msg', 'Evento' . $event->title . ' Criado com Sucesso!');
    }

    public function dashboard()
    {
        $userLogged = $this->getUserLogged();

        $events = Event::where('user_Id', $userLogged->id)->get();

        $eventsAsParticipant = $userLogged->eventsAsParticipant;

        return view('events.dashboard', [
            'events' => $events, 
            'eventsAsParticipant' => $eventsAsParticipant
        ]);
    }

    public function edit($id)
    {
        $user = $this->getUserLogged();

        $event = Event::findOrFail($id);

        if ($user->id != $event->user_id) {
            return redirect('/dashboard')->with('msg', 'Evento não encontrado!');
        }
        

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        // image upload 
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $ext = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now') . '.' . $ext);

            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;
        }
       
        $event = Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento removido com sucesso!');
    }

    public function joinEvent($id)
    {
        $user = $this->getUserLogged();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title);
    }

    public function leaveEvent($id)
    {
        $user = $this->getUserLogged();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
