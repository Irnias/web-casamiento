<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js"></script>
</head>
<body class="bg-slate-50 text-slate-800">

<nav class="bg-white shadow-sm border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <span class="font-bold text-xl text-slate-700 tracking-tight">{{ $event->name }}</span>
            </div>
            <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-500 hidden md:block">
                        Logueado como: <strong>{{ Auth::user()->name }}</strong>
                    </span>

                <a href="{{ route('auth.logout') }}"
                   class="text-sm font-medium text-red-600 hover:text-red-800 transition">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Panel de Control</h1>
            <p class="text-slate-500 mt-1">
                Gestionando la boda de <span class="text-indigo-600 font-medium">{{ $stats['owners_names'] }}</span>
            </p>
        </div>

        <a href="{{ url('/' . $event->slug . '/invitacion') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <span>Ver web pública</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
            <dt class="text-sm font-medium text-slate-500 truncate">Total Invitados</dt>
            <dd class="mt-1 text-3xl font-semibold text-slate-900">{{ $stats['guests_count'] }}</dd>
            <div class="absolute top-4 right-4 text-indigo-100">
                <svg class="w-16 h-16 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
            <dt class="text-sm font-medium text-slate-500 truncate">Fotos por Aprobar</dt>
            <dd class="mt-1 text-3xl font-semibold {{ $stats['photos_pending'] > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                {{ $stats['photos_pending'] }}
            </dd>
            @if($stats['photos_pending'] > 0)
                <p class="text-xs text-amber-600 mt-1 font-medium">¡Requiere atención!</p>
            @else
                <p class="text-xs text-emerald-600 mt-1">Todo al día ✨</p>
            @endif
            <div class="absolute top-4 right-4 text-amber-100">
                <svg class="w-16 h-16 opacity-20 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
            <dt class="text-sm font-medium text-slate-500 truncate">Estado del Evento</dt>
            <dd class="mt-1 text-3xl font-semibold text-slate-900">Activo</dd>
            <p class="text-xs text-slate-400 mt-1">{{ $event->event_date->format('d/m/Y') }}</p>
        </div>
    </div>

    <h2 class="text-lg font-bold text-slate-900 mb-4">Acciones Rápidas</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        @if(Auth::user()->hasPermission(App\Enums\PermissionEnum::from('manage_photos'), $event))
            <a href="#" class="group flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-indigo-300 hover:shadow-md transition">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Fotos</h3>
                    <p class="text-xs text-slate-500">Moderar galería</p>
                </div>
            </a>
        @endif

        @if(Auth::user()->hasPermission(App\Enums\PermissionEnum::from('view_guests'), $event))
            <a href="#" class="group flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-pink-300 hover:shadow-md transition">
                <div class="p-3 bg-pink-50 text-pink-600 rounded-lg group-hover:bg-pink-600 group-hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Invitados</h3>
                    <p class="text-xs text-slate-500">Ver lista y RSVP</p>
                </div>
            </a>
        @endif

        @if(Auth::user()->hasPermission(App\Enums\PermissionEnum::from('configure_event'), $event))
            <a href="#" class="group flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-400 hover:shadow-md transition">
                <div class="p-3 bg-slate-100 text-slate-600 rounded-lg group-hover:bg-slate-700 group-hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.115 1.115 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.212 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.115 1.115 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.115 1.115 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Ajustes</h3>
                    <p class="text-xs text-slate-500">Configurar evento</p>
                </div>
            </a>
        @endif

    </div>

</main>

</body>
</html>
