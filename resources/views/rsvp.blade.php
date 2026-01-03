<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Invitación - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDFBF7] text-slate-800 min-h-screen">

{{-- Navegación --}}
<nav class="flex justify-between items-center p-4 max-w-lg mx-auto">
    <div class="text-xs tracking-widest uppercase font-semibold text-slate-400">
        {{ $event->name }}
    </div>
    <form action="{{ route('rsvp.logout', $event) }}" method="POST">
        @csrf
        <button class="text-xs text-red-400 hover:text-red-600 underline">Cerrar Sesión</button>
    </form>
</nav>

<main class="p-4 max-w-lg mx-auto pb-20">

    {{-- Header Bienvenida --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-serif text-slate-900 mb-2">
            Hola, {{ $guests->pluck('name')->join(', ', ' y ') }}
        </h1>
        <p class="text-slate-500 text-sm">
            Por favor, confirmen la asistencia de cada uno.
        </p>
    </div>

    {{-- Mensaje de Éxito Global --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100 flex items-center gap-2 animate-bounce">
            <span>✨</span> {{ session('success') }}
        </div>
    @endif

    {{-- BUCLE: Tarjeta por Invitado --}}
    <div class="space-y-6">
        @foreach($guests as $guest)
            <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden relative">

                {{-- Banda lateral de color según estado --}}
                <div class="absolute left-0 top-0 bottom-0 w-1.5
                        {{ $guest->attendance === 'confirmed' ? 'bg-emerald-400' : ($guest->attendance === 'declined' ? 'bg-red-400' : 'bg-slate-200') }}">
                </div>

                <div class="p-6 pl-8">
                    {{-- Encabezado de la Tarjeta --}}
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">{{ $guest->name }}</h2>
                            <p class="text-xs text-slate-400 uppercase tracking-wide mt-1">
                                @if($guest->attendance === 'confirmed')
                                    Confirmado
                                @elseif($guest->attendance === 'declined')
                                    No asistirá
                                @else
                                    Pendiente de respuesta
                                @endif
                            </p>
                        </div>
                        <div class="text-2xl">
                            @if($guest->attendance === 'confirmed') 🥂
                            @elseif($guest->attendance === 'declined') 😢
                            @else 💌 @endif
                        </div>
                    </div>

                    {{-- Formulario Individual --}}
                    <form action="{{ route('rsvp.submit', $event) }}" method="POST" class="space-y-5">
                        @csrf
                        {{-- ID Oculto para saber a quién actualizamos --}}
                        <input type="hidden" name="guest_id" value="{{ $guest->id }}">

                        {{-- Botones Sí/No --}}
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="attendance" value="confirmed" class="peer sr-only"
                                    {{ $guest->attendance === 'confirmed' ? 'checked' : '' }}>
                                <div class="py-2 px-3 rounded-lg border border-stone-200 text-center text-sm font-medium hover:bg-stone-50 peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 transition">
                                    ¡Sí, voy!
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="attendance" value="declined" class="peer sr-only"
                                    {{ $guest->attendance === 'declined' ? 'checked' : '' }}>
                                <div class="py-2 px-3 rounded-lg border border-stone-200 text-center text-sm font-medium hover:bg-stone-50 peer-checked:bg-white peer-checked:text-red-600 peer-checked:border-red-200 transition">
                                    No puedo
                                </div>
                            </label>
                        </div>

                        {{-- Inputs Extras (Solo se muestran si NO ha rechazado, o siempre) --}}
                        <div class="space-y-3 pt-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">
                                    Restricciones Alimentarias
                                </label>
                                <input type="text" name="dietary_restrictions"
                                       value="{{ $guest->dietary_restrictions }}"
                                       placeholder="Ninguna"
                                       class="w-full bg-slate-50 border border-stone-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400 transition">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">
                                    Preferencia de Bebida
                                </label>
                                <input type="text" name="drink_preferences"
                                       value="{{ $guest->drink_preferences }}"
                                       placeholder="¿Qué tomas?"
                                       class="w-full bg-slate-50 border border-stone-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-slate-400 transition">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold shadow-sm hover:bg-slate-50 hover:text-slate-800 active:scale-[0.98] transition">
                            Guardar confirmación de {{ explode(' ', $guest->name)[0] }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer --}}
    <div class="mt-12 text-center pb-8">
        <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-white rounded-full shadow-sm text-sm font-medium text-slate-600 border border-slate-200 hover:shadow-md transition">
            <span>📍</span> Ver ubicación y Mapa
        </a>
    </div>

</main>

</body>
</html>
