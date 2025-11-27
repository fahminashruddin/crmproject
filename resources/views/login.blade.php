<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM Percetakan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" x-data="{ showPassword: false, showCredentials: false }">
        <div class="max-w-md w-full space-y-8">

            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto h-12 w-12 text-blue-600">
                    <path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1"/><path d="M12 18h1"/><path d="M7 18h1"/>
                </svg>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">CRM Percetakan</h2>
                <p class="mt-2 text-sm text-gray-600">Masuk ke sistem manajemen</p>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-200">
                <div class="mb-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">@if(!empty(
                        $roleRecord)) Login - {{ $roleRecord->nama_role }} @else Login @endif</h3>
                    <p class="mt-1 text-sm text-gray-500">Masukkan email dan password Anda</p>
                </div>

                @error('email')
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 mr-2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span class="block sm:inline text-sm">{{ $message }}</span>
                </div>
                @enderror

                <form class="space-y-6" action="{{ route($loginPostRoute ?? 'login.post') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                placeholder="Masukkan password">

                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" style="display: none;"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <button @click="showCredentials = !showCredentials" type="button" class="px-2 bg-white text-blue-600 hover:text-blue-500 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                Lihat Credential Demo
                            </button>
                        </div>
                    </div>

                    <div x-show="showCredentials" x-transition class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-4 text-sm text-blue-700" style="display: none;">
                        <p class="font-semibold mb-2">Credential untuk testing:</p>
                        <ul class="space-y-1">
                            <li><strong>Admin:</strong> admin@example.com / password</li>
                            <li><strong>Desain:</strong> desain1@example.com / password</li>
                            <li><strong>Produksi:</strong> produksi1@example.com / password</li>
                            <li><strong>Manajemen:</strong> manajemen1@example.com / password</li>

                            {{-- <li><strong>Admin:</strong> admin@percetakan.com / admin123</li>
                            <li><strong>Desain:</strong> design@percetakan.com / design123</li>
                            <li><strong>Produksi:</strong> production@percetakan.com / production123</li>
                            <li><strong>Manajemen:</strong> manager@percetakan.com / manager123</li> --}}
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
