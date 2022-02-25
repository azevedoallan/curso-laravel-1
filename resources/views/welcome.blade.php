@extends('layouts.main')

@section('title', 'HDC Events')

@section('content')
   <div id="search-container" class="col-md-12">
      <h1>Busque um evento</h1>
      <form action="/" method="GET">
         <input type="text" id="search" name="search" value="{{$search}}" class="form-control" placeholder="Procurar">
      </form>
   </div>

   <div id="events-container" class="col-md-12">
      @if(!$search)
      <h2>Próximos Eventos</h2>
      <p class="subtitle">Veja os Eventos dos proximos dias</p>
      @else
      <h2>Bucando por: {{$search}}</h2>
      @endif
      <div id="cards-container" class="row">
         @foreach($events as $event)
            <div class="card col-md-3">
               <img src="/img/events/{{$event->image}}" alt="{{ $event->title }}">
               <div class="card-body">
                  <p class="card-date">
                     {{ date('d/m/Y', strtotime($event->date)) }}
                  </p>
                  <h5 class="card-title">
                     {{ $event->title }}
                  </h5>
                  <p class="card-participants">{{ count($event->users) }} Participante(s)</p>
                  <a href="/events/show/{{ $event->id }}" class="btn btn-primary"> Saber mais</a>
               </div>
            </div>
         @endforeach
         @if (count($events) == 0 && $search)
            <p>
               Não foi possível achar nenhum evento com a palavra "{{$search}}"
               <a href="/">Ver todos</a>
            </p>
         @elseif(count($events) == 0)
            <p>Não há eventos disponíveis.</p>
         @endif
      </div>
   </div>
@endsection