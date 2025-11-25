<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Desain') - CRM Percetakan</title>
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
                <a href="/desain/dashboard" class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.5a2 2 0 00-1 .268m-8.268.268a2 2 0 011 .268h10.5a2 2 0 002 2v4a2 2 0 01-2 2h-12a2 2 0 01-2-2v-4a2 2 0 011-1.732"/></svg>
                    <span class="text-lg font-semibold">Desain</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
                <a href="/desain/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('desain/dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="/desain/designs" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('desain/designs*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="palette" class="w-5 h-5"></i>
                    <span class="text-sm">Desain Pesanan</span>
                </a>

                <a href="/desain/revisions" class="flex items-center gap-3 px-3 py-2 rounded-md {{ request()->is('desain/revisions*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i data-lucide="undo-2" class="w-5 h-5"></i>
                    <span class="text-sm">Revisi</span>
                </a>
            </nav>

            <div class="px-4 py-4 border-t">
                <div class="text-xs text-gray-500">Desain Panel v1.0</div>
            </div>
        </div>
    </aside>

    <!-- Topbar (fixed) -->
    <header class="fixed left-64 right-0 top-0 h-16 bg-white border-b flex items-center justify-end px-6">
        <div class="flex items-center gap-4">
            <div class="text-right">
                <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Designer' }}</div>
                <div class="text-xs text-gray-500">Desain</div>
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
