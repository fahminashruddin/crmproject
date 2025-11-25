<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Produksi') - CRM Percetakan</title>
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
                <a href="/produksi/dashboard" class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-lg font-semibold">Produksi</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
                <a href="/produksi/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('produksi/dashboard') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="/produksi/productions" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('produksi/productions*') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                    <span class="text-sm">Proses Produksi</span>
                </a>

                <a href="/produksi/issues" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('produksi/issues*') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    <span class="text-sm">Kendala</span>
                </a>
            </nav>

            <div class="px-4 py-4 border-t">
                <div class="text-xs text-gray-500">Produksi Panel v1.0</div>
            </div>
        </div>
    </aside>

    <!-- Topbar (fixed) -->
    <header class="fixed left-64 right-0 top-0 h-16 bg-white border-b flex items-center justify-end px-6">
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Production' }}</div>
                <div class="text-xs text-gray-500">Produksi</div>
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
