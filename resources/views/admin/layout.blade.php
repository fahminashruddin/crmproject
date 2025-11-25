<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin') - CRM Percetakan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{--bg:#f8fafc; font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial}
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    @include('partials.navbar')

    <main class="ml-64 pt-20 px-8"> <!-- offset untuk sidebar + topbar -->
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Lucide (vanilla) - load and initialize with fallback -->
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
