<!-- Sidebar (fixed) -->
<aside class="fixed inset-y-0 left-0 w-64 bg-white border-r">
    <div class="h-full flex flex-col">
        <div class="h-16 px-6 flex items-center border-b">
            <a href="/dashboard" class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3v4M8 3v4"/></svg>
                <span class="text-lg font-semibold">CRM Percetakan</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-md bg-gray-900 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="/pesanan" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                <span class="text-sm">Kelola Pesanan</span>
            </a>

            <a href="/pembayaran" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/></svg>
                <span class="text-sm">Pembayaran</span>
            </a>

            <a href="/users" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.295.56 6.121 1.528M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm">Manajemen User</span>
            </a>

            <a href="/settings" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                <span class="text-sm">Pengaturan</span>
            </a>

            <a href="/notifikasi" class="flex items-center gap-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                <span class="text-sm">Notifikasi</span>
            </a>
        </nav>

        <div class="px-4 py-4 border-t">
            <div class="text-xs text-gray-500">Versi 1.0</div>
        </div>
    </div>
</aside>

<!-- Topbar (fixed) -->
<header class="fixed left-64 right-0 top-0 h-16 bg-white border-b flex items-center justify-end px-6">
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-3 text-sm text-gray-600">
            <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span>Online</span>
            </span>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right">
                <div class="text-sm font-medium">{{ auth()->user()->name ?? 'Administrator' }}</div>
                <div class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-gray-800 border-l pl-4">⎋</button>
            </form>
        </div>
    </div>
</header>
