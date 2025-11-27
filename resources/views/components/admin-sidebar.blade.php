<aside class="w-64 border-r border-gray-200 bg-white fixed inset-y-0 left-0 z-50 hidden md:block transition-transform duration-300" id="sidebar">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <i data-lucide="bar-chart-2" class="h-6 w-6 mr-2 text-slate-900"></i>
        <span class="font-bold text-lg tracking-tight">CRM Percetakan</span>
    </div>

    <nav class="p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
           {{ Route::is('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
            <i data-lucide="layout-dashboard" class="mr-3 h-5 w-5"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.orders') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
           {{ Route::is('admin.orders') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
            <i data-lucide="shopping-cart" class="mr-3 h-5 w-5"></i>
            Kelola Pesanan
        </a>

        <a href="{{ route('admin.payments') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
           {{ Route::is('admin.payments') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
            <i data-lucide="credit-card" class="mr-3 h-5 w-5"></i>
            Pembayaran
        </a>

        <a href="{{ route('admin.users') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
           {{ Route::is('admin.users') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
            <i data-lucide="users" class="mr-3 h-5 w-5"></i>
            Manajemen User
        </a>

        <a href="{{ route('admin.settings') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
           {{ Route::is('admin.settings') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
            <i data-lucide="settings" class="mr-3 h-5 w-5"></i>
            Pengaturan
        </a>

         <a href="{{ route('admin.notifications') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors
            {{ Route::is('admin.notifications') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
             <i data-lucide="bell" class="mr-3 h-5 w-5"></i>
             Notifikasi
         </a>
    </nav>
</aside>
