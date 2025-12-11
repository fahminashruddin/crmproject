<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Manajemen') - CRM Percetakan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>

<body class="bg-white min-h-screen text-slate-900">

    <div class="min-h-screen flex flex-col">

        {{-- HEADER --}}
        <header class="h-16 border-b bg-white/95 backdrop-blur flex items-center px-6 sticky top-0 z-50">
            <button class="mr-4 md:hidden"><i data-lucide="menu" class="h-5 w-5"></i></button>

            <div class="flex items-center gap-2">
                <i data-lucide="factory" class="h-6 w-6 text-slate-900"></i>
                <h1 class="text-xl font-bold tracking-tight">CRM Percetakan</h1>
            </div>

            <div class="ml-auto flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-sm text-slate-500">Online</span>
                </div>

                <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-full">
                    <i data-lucide="bell" class="h-5 w-5"></i>
                </button>

                <div class="text-right hidden md:block">
                    <p class="text-sm font-medium">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-slate-500">Manajemen</p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full" title="Keluar">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- WRAPPER --}}
        <div class="flex flex-1 overflow-hidden">

            {{-- SIDEBAR --}}
            <aside class="w-64 border-r bg-white hidden md:block overflow-y-auto">
                <div class="flex flex-col py-4">
                    <nav class="space-y-1 px-3">

                        {{-- Dashboard --}}
                        <a href="{{ route('manajemen.dashboard') }}"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                           {{ request()->routeIs('manajemen.dashboard') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                            Dashboard
                        </a>

                        {{-- Laporan --}}
                        <a href="{{ url('/manajemen/laporan') }}"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                           {{ request()->is('manajemen/laporan*') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            Laporan
                        </a>

                        {{-- Analytics --}}
                        <a href="{{ url('/manajemen/analytics') }}"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                           {{ request()->is('manajemen/analytics*') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            <i data-lucide="bar-chart-2" class="h-4 w-4"></i>
                            Analytics
                        </a>

                        {{-- Export Data --}}
                        <a href="{{ url('/manajemen/export') }}"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                           {{ request()->is('manajemen/export*') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Export Data
                        </a>

                    </nav>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 overflow-auto bg-white p-6">
                <div class="container mx-auto max-w-6xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script> lucide.createIcons(); </script>
</body>
</html>
