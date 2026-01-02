<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitados - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<nav class="bg-white shadow-sm border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard', $event) }}" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span class="font-medium">Volver</span>
                </a>
            </div>
            <div class="font-bold text-lg">Gestión de Invitados</div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Columna 1: Formulario de Alta --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 sticky top-6">
                <h2 class="text-xl font-bold mb-4">Agregar Invitado</h2>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('guests.store', $event) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nombre Completo</label>
                        <input type="text" name="name" required placeholder="Ej: Tía Marta"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Código de Grupo (Opcional)
                        </label>
                        <input type="text" name="invitation_code" placeholder="Dejar vacío para generar nuevo"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border p-2 bg-slate-50">
                        <p class="text-xs text-slate-500 mt-1">Si quieres agregarlo a una familia existente, pega su código aquí.</p>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition font-medium">
                        Guardar Invitado
                    </button>
                </form>
            </div>
        </div>

        {{-- Columna 2: Lista de Invitados --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Código (Familia)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($guests as $guest)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">{{ $guest->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $guest->invitation_code }}
                                        </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($guest->attendance === 'confirmed')
                                    <span class="text-green-600 text-sm font-bold">Confirmado</span>
                                @elseif($guest->attendance === 'declined')
                                    <span class="text-red-600 text-sm">Rechazado</span>
                                @else
                                    <span class="text-slate-400 text-sm italic">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('guests.destroy', [$event, $guest]) }}" method="POST" onsubmit="return confirm('¿Borrar a {{ $guest->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                No hay invitados aún. ¡Agrega el primero!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
</body>
</html>
