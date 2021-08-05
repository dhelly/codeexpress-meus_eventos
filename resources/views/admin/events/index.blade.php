@extends('layouts.app')

@section('title') Meus Eventos  @endsection

@section('content')

    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center my-5">
            <h2>Meus eventos</h2>
            <a href="{{ route('admin.events.create') }}" class="btn btn-success">Criar Evento</a>
        </div>
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Evento</th>
                        <th>Criado Em</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.events.edit', ['event' => $event->id]) }}" class="btn btn-warning">Editar</a>
                                <form action="{{ route('admin.events.destroy', ['event' => $event->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger mx-2" onclick="return confirm('Você deseja realmente remover este item')">Remover</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Nenhum evento cadastrado</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
            {{ $events->links() }}
        </div>
    </div>
@endsection
