<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Desain') - CRM Percetakan</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white min-h-screen text-slate-900">

    <div class="min-h-screen flex flex-col">
        
        {{-- HEADER --}}
        <header class="h-16 border-b bg-white/90 backdrop-blur flex items-center px-6 sticky top-0 z-50">
            <button class="mr-4 md:hidden">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

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
                    <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Desainer' }}</p>
                    <p class="text-xs text-slate-500">Tim Desain</p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- WRAPPER --}}
        <div class="flex flex-1 overflow-hidden">
            
            {{-- SIDEBAR --}}
            <aside class="w-60 border-r bg-white hidden md:block overflow-y-auto">
                <div class="flex flex-col py-4">
                    <nav class="space-y-1 px-3">

                        <a href="{{ route('desain.dashboard') }}"
    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
    {{ request()->routeIs('desain.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
    <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
    Dashboard
</a>

<a href="{{ route('desain.kelola') }}"
    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
    {{ request()->routeIs('desain.kelola') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
    <i data-lucide="palette" class="h-4 w-4"></i>
    Kelola Desain
</a>

                        <a href="{{ route('desain.pengaturan') }}" 
                            class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                            {{ request()->routeIs('desain.pengaturan') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100' }}">
                            <i data-lucide="archive" class="h-4 w-4"></i>
                            Template Desain
                        </a>

                        <a href="{{ route('desain.riwayat') }}"
                            class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                            {{ request()->routeIs('desain.riwayat') 
                                ? 'bg-slate-900 text-white' 
                                : 'text-slate-700 hover:bg-slate-100' }}">
                            <i data-lucide="history" class="h-4 w-4"></i>
                            Riwayat Desain
                        </a>

                    </nav>
                </div>
            </aside>

            {{-- MAIN CONTENT FULL WIDTH --}}
            <main class="flex-1 overflow-auto bg-white p-8">
                <div class="w-full">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>