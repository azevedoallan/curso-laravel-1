@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

<div class="col-md-12 offset-md-1 dashboard-title-container">
    <h1>
        Meus Eventos
    </h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
    @if(count($events) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th scode="col">
                        #
                    </th>
                    <th scode="col">
                        Nome
                    </th>
                    <th scode="col">
                        Participantes
                    </th>
                    <th scode="col">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td scropt="row">
                            {{ $loop->index +1 }}
                        </td>
                        <td scropt="row">
                            {{ $event->title }}
                        </td>
                        <td>
                            {{ count($event->users) }}
                        </td>
                        <td>
                            <a href="/events/edit/{{ $event->id }}" class="btn btn-info edit-btn"><ion-icon name="create-outline"></ion-icon>Editar</a>
                            <form action="/events/{{ $event->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger delete-btn">
                                    <ion-icon name="trash-outline">
                                    </ion-icon>
                                    Deletar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Você ainda não tem eventos, <a href="/events/create">Criar Evento</a></p>
    @endif
</div>

<div class="col-md-12 offset-md-1 dashboard-title-container">
    <h1>
        Eventos que estou participando
    </h1>
</div>
<div class="col-md-10 offset-md-1 dashboard-events-container">
        @if (count($eventsAsParticipant) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th scode="col">
                            #
                        </th>
                        <th scode="col">
                            Nome
                        </th>
                        <th scode="col">
                            Participantes
                        </th>
                        <th scode="col">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventsAsParticipant as $event)
                        <tr>
                            <td scropt="row">
                                {{ $loop->index +1 }}
                            </td>
                            <td scropt="row">
                                {{ $event->title }}
                            </td>
                            <td>
                                {{ count($event->users) }}
                            </td>
                            <td>
                                <form action="/events/leave/{{ $event->id }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger delete-btn">
                                        <ion-icon name="trash-outline"></ion-icon>
                                        Sair do evento
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>você ainda não está participando de nenhum evento, <a href="/">Veja todos os eventos</a></p>
        @endif
    </div>
@endsection