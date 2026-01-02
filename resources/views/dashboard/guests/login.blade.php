<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar a la Boda - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Fuente elegante para el título (opcional) --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
</head>
<body class="bg-[#FDFBF7] text-slate-800 min-h-screen flex flex-col">

<div class="flex-grow flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl overflow-hidden border border-stone-100">

        {{-- Foto de Portada (Opcional) --}}
        <div class="h-32 bg-stone-200 flex items-center justify-center bg-cover bg-center"
             style="background-image: url('https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');">
            <div class="bg-black/30 w-full h-full flex items-center justify-center backdrop-blur-[2px]">
                <span class="text-white font-medium tracking-widest text-xs uppercase">Rami & Meli</span>
            </div>
        </div>

        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif text-stone-800 mb-2" style="font-family: 'Playfair Display', serif;">
                    Bienvenido
                </h1>
                <p class="text-stone-500 text-sm">
                    Ingresa el código que recibiste en tu invitación para confirmar asistencia.
                </p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('rsvp.authenticate', $event) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-2">
                        Código de Invitación
                    </label>
                    <input type="text" name="code"
                           placeholder="Ej: BODA-1234"
                           class="w-full text-center text-2xl font-bold tracking-widest uppercase border-b-2 border-stone-200 py-2 focus:outline-none focus:border-stone-800 transition placeholder-stone-300"
                           autofocus required>
                </div>

                <button type="submit"
                        class="w-full bg-stone-900 text-white font-medium py-3 rounded-lg hover:bg-stone-700 transition duration-300 shadow-lg shadow-stone-900/20">
                    Ingresar a la Boda
                </button>
            </form>

            <p class="text-center text-xs text-stone-400 mt-6">
                ¿Problemas para entrar? Contacta a los novios.
            </p>
        </div>
    </div>
</div>

</body>
</html>
