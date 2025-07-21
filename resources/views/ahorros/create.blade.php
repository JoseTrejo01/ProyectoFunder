@extends('adminlte::page')

@section('content_header')
    <h1>Nuevo Registro de Ahorros</h1>
@stop

@section('content')
    <form action="{{ route('ahorros.store') }}" method="POST">
        @include('ahorros.partials.form', ['modo' => 'crear'])

        <a href="{{ route('ahorros.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
    </form>
@stop

@section('js')
<script>
    $(function () {
        $('#ahorroTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection
