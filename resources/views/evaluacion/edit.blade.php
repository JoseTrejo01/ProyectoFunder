@extends('adminlte::page')

@section('title', 'Editar Evaluación')

@section('content_header')
    <h1>Editar Evaluación</h1>
@endsection

@section('content')
    <form action="{{ route('evaluacion.update', $evaluacion) }}" method="POST">
        @csrf
        @method('PUT')
        @include('evaluacion.partials.form')
    </form>
@endsection
