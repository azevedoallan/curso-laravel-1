@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

<div class="col-md-12 offset-md-1 dashboard-title-container">
    <h1>
        Meus Eventos
    </h1>

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
                            <td>
                               {{ count($event->users) }}
                            </td>
                            <td>
                               <a href="#">Editar</a>
                               <a href="#">Deletar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Você ainda não tem eventos, <a href="/events/create">Criar Evento</a>/p>
        @endif
    </div>
</div>
@endsection