<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM Percetakan - Admin</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; } /* Mencegah kedip saat loading */
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

<div class="flex min-h-screen">

    @include('components.admin-sidebar')

    <div class="flex-1 flex flex-col md:pl-64 transition-all duration-300">

        @include('components.admin-header')

        <main class="p-8">
            @yield('content')
        </main>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>
