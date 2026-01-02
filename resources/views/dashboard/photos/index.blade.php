<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Fotos - {{ $event->name }}</title>
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
                    <span class="font-medium">Volver al Dashboard</span>
                </a>
            </div>
            <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-500 hidden md:block">
                        Admin: <strong>{{ Auth::user()->name }}</strong>
                    </span>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Encabezado de la Sección --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">📸 Moderación de Fotos</h1>
        <p class="text-slate-500 mt-1">
            Aprueba las fotos para que aparezcan en la galería pública o recházalas.
        </p>
    </div>

    {{-- Mensajes de Feedback (Éxito) --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 border border-green-200 rounded-lg flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Grid de Fotos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($photos as $photo)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden group">

                {{-- Imagen --}}
                <div class="relative aspect-square bg-slate-100">
                    {{-- Asumimos que guardas la ruta en 'path'. Ajusta si usas otro nombre --}}
                    <img src="{{ asset('storage/' . $photo->path) }}"
                         alt="Foto subida"
                         class="w-full h-full object-cover">

                    {{-- Badge de quien la subió --}}
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 pt-8">
                        <p class="text-white text-xs truncate">
                            Subida por: <span class="font-bold">{{ $photo->uploader?->name ?? 'Invitado' }}</span>
                        </p>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="p-4 flex gap-3">
                    {{-- Botón APROBAR --}}
                    <form action="{{ route('photos.approve', [$event, $photo]) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center gap-1 bg-emerald-50 text-emerald-600 py-2 px-3 rounded-lg text-sm font-semibold hover:bg-emerald-600 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Aprobar
                        </button>
                    </form>

                    {{-- Botón BORRAR --}}
                    <form action="{{ route('photos.destroy', [$event, $photo]) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Seguro que quieres borrar esta foto permanentemente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex justify-center items-center gap-1 bg-red-50 text-red-600 py-2 px-3 rounded-lg text-sm font-semibold hover:bg-red-600 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Borrar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            {{-- Estado Vacío --}}
            <div class="col-span-full py-12 flex flex-col items-center justify-center text-center text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mb-4 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <p class="text-lg font-medium text-slate-600">No hay fotos pendientes</p>
                <p class="text-sm">¡Buen trabajo! Has revisado todas las fotos.</p>
            </div>
        @endforelse

    </div>
</main>

</body>
</html>
