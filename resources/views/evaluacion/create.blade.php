@extends('adminlte::page')

@section('title', 'Crear Evaluación')

@section('content_header')
    <h1>Crear Evaluación</h1>
@endsection

@section('content')
    <form action="{{ route('evaluacion.store') }}" method="POST">
        @csrf
        @include('evaluacion.partials.form')
    </form>
@endsection