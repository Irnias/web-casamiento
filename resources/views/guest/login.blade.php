<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar a la Boda - {{ $event->name }}</title>

    {{-- 1. Script de Tailwind CSS (Fundamental para que se vea bien) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- 2. Fuentes Elegantes --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDFBF7] text-slate-800 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-white shadow-xl shadow-stone-200/50 rounded-3xl overflow-hidden border border-stone-100">

    {{-- Imagen de Portada --}}
    <div class="h-40 bg-stone-200 relative">
        {{-- Puedes cambiar esta URL por una foto real de los novios --}}
        <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
             alt="Boda" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                <span class="text-white text-sm font-medium tracking-[0.2em] uppercase drop-shadow-md">
                    {{ $event->name }}
                </span>
        </div>
    </div>

    <div class="p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-serif text-slate-800 mb-2">Bienvenido</h1>
            <p class="text-slate-500 text-sm">
                Por favor, ingresa tu código de invitación para confirmar asistencia.
            </p>
        </div>

        {{-- Mensajes de Error --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-sm rounded-xl flex gap-2 items-start animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('rsvp.authenticate', $event) }}" method="POST">
            @csrf

            <div class="mb-8 relative">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2 text-center">
                    Código de Invitación
                </label>
                <input type="text" name="code"
                       placeholder="Ej: BODA-1234"
                       class="w-full text-center text-3xl font-bold text-slate-800 tracking-widest uppercase border-b-2 border-stone-200 py-3 focus:outline-none focus:border-slate-800 transition-colors placeholder-stone-200 bg-transparent"
                       autofocus required>
            </div>

            <button type="submit"
                    class="w-full bg-slate-900 text-white font-medium py-4 rounded-xl hover:bg-slate-800 transition-all transform active:scale-[0.98] shadow-lg shadow-slate-900/20">
                Ingresar a la Boda
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-xs text-stone-400">
                ¿Tienes problemas para ingresar? <br>
                Contacta a los novios.
            </p>
        </div>
    </div>
</div>

</body>
</html>
