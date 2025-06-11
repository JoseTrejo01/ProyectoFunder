@extends('adminlte::page')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Panel de Control - Usuarios</h1>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
              class="bg-red-600 text-white px-5 py-2 rounded hover:bg-red-700 transition" 
              aria-label="Cerrar sesión">
                Cerrar sesión
            </button>
        </form>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-left table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-700 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 font-semibold text-gray-700 uppercase tracking-wider">Correo Electrónico</th>
                    <th class="px-6 py-3 font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 font-semibold text-gray-700 uppercase tracking-wider">Última Conexión</th>
                </tr>
            </thead>
            
        </table>
    </div>
</div>
@endsection