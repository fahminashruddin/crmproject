<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Manajemen') - CRM Percetakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{--bg:#f8fafc; font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial}
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar (fixed) -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r">
        <div class="h-full flex flex-col">
            <div class="h-16 px-6 flex items-center border-b">
                <a href="/manajemen/dashboard" class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span class="text-lg font-semibold">Manajemen</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
                <a href="/manajemen/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('manajemen/dashboard') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="/manajemen/reports" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('manajemen/reports*') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="text-sm">Laporan</span>
                </a>

                <a href="/manajemen/analytics" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('manajemen/analytics*') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span class="text-sm">Analitik</span>
                </a>
            </nav>

            <div class="px-4 py-4 border-t">
                <div class="text-xs text-gray-500">Manajemen Panel v1.0</div>
            </div>
        </div>
    </aside>

    <!-- Topbar (fixed) -->
    <header class="fixed left-64 right-0 top-0 h-16 bg-white border-b flex items-center justify-end px-6">
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Manager' }}</div>
                <div class="text-xs text-gray-500">Manajemen</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-800 border-l pl-4">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 pt-20 px-8">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Lucide initialization -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.253.0/dist/lucide.min.js"></script>
    <script>
        (function initLucide() {
            function doReplace() {
                try {
                    if (window.lucide && typeof window.lucide.replace === 'function') {
                        window.lucide.replace({ 'stroke-width': 1.5, width: 20, height: 20 });
                    }
                } catch (e) {}
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', doReplace);
            } else {
                doReplace();
            }
            setTimeout(function () {
                if (!window.lucide) {
                    var s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/lucide@0.253.0/dist/lucide.min.js';
                    s.onload = doReplace;
                    document.head.appendChild(s);
                }
            }, 500);
        })();
    </script>

</body>
</html>
