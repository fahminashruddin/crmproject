<header class="h-16 flex items-center justify-between px-8 bg-white border-b border-gray-100 sticky top-0 z-40">
    <button class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg" onclick="document.getElementById('sidebar').classList.toggle('hidden')">
        <i data-lucide="menu" class="h-6 w-6"></i>
    </button>

    <div class="flex-1"></div>

    <div class="flex items-center gap-6">
        <div class="hidden sm:flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-sm text-slate-500 font-medium">Online</span>
        </div>

        <button class="relative text-slate-500 hover:text-slate-700 transition-colors">
            <i data-lucide="bell" class="h-5 w-5"></i>
            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
        </button>

        <div class="flex items-center gap-3 border-l pl-6 border-gray-200">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">Administrator</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 hover:bg-red-50 text-slate-500 hover:text-red-600 rounded-full transition-colors" title="Logout">
                    <i data-lucide="log-out" class="h-5 w-5"></i>
                </button>
            </form>
        </div>
    </div>
</header>
