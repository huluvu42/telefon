{{-- resources/views/components/app-layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Telefonverzeichnis') }}</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation - Max 1920px für Kontakte-Seite, begrenzt für andere -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="{{ request()->routeIs('contacts.*') ? 'w-full max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8' }}">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('contacts.index') }}" class="text-xl font-semibold text-gray-900">
                            📞 Telefonverzeichnis
                        </a>
                        
                        <div class="hidden md:block ml-10">
                            <div class="flex space-x-8">
                                <a href="{{ route('contacts.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('contacts.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                    Kontakte
                                </a>
                                <a href="{{ route('mobile.index') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium {{ request()->routeIs('mobile.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                                    Mobiltelefone
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            @hasrole('admin')
                                <!-- Admin Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium flex items-center {{ request()->routeIs('admin.*') ? 'text-blue-600' : '' }}">
                                        Admin
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                         x-cloak>
                                        <div class="py-1">
                                            <a href="{{ route('admin.dashboard') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-50 text-blue-600' : '' }}">
                                                📊 Dashboard
                                            </a>
                                            <div class="border-t border-gray-100"></div>
                                            <a href="{{ route('admin.users.index') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-50 text-blue-600' : '' }}">
                                                👥 Benutzer verwalten
                                            </a>
                                            <a href="{{ route('admin.contacts.index') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.contacts.*') ? 'bg-gray-50 text-blue-600' : '' }}">
                                                📞 Kontakte verwalten
                                            </a>
                                            <a href="{{ route('admin.uploads.index') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.uploads.*') ? 'bg-gray-50 text-blue-600' : '' }}">
                                                📤 Uploads verwalten
                                            </a>
                                            <div class="border-t border-gray-100"></div>
                                            <a href="{{ route('admin.users.create') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                ➕ Neuer Benutzer
                                            </a>
                                            <a href="{{ route('admin.uploads.create') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                📤 Excel hochladen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endhasrole
                            
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center space-x-2">
                                    <!-- User Avatar -->
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                                    @hasrole('admin')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Admin
                                        </span>
                                    @endhasrole
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                                        Abmelden
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                                Anmelden
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div class="md:hidden border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('contacts.index') }}" 
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('contacts.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Kontakte
                    </a>
                    <a href="{{ route('mobile.index') }}" 
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('mobile.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Mobiltelefone
                    </a>
                    @auth
                        @hasrole('admin')
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</div>
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                                    📊 Dashboard
                                </a>
                                <a href="{{ route('admin.users.index') }}" 
                                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                                    👥 Benutzer
                                </a>
                                <a href="{{ route('admin.contacts.index') }}" 
                                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                                    📞 Kontakte
                                </a>
                                <a href="{{ route('admin.uploads.index') }}" 
                                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                                    📤 Uploads
                                </a>
                            </div>
                        @endhasrole
                    @endauth
                </div>
            </div>
        </nav>
        
        <!-- Page Content - Max 1920px für Kontakte-Seite, begrenzt für andere -->
        <main class="{{ request()->routeIs('contacts.*') ? 'w-full max-w-[1920px] mx-auto py-6' : 'max-w-7xl mx-auto py-6 sm:px-6 lg:px-8' }}">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded {{ request()->routeIs('contacts.*') ? 'mx-4 sm:mx-6 lg:mx-8 xl:mx-12 2xl:mx-16' : '' }}">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded {{ request()->routeIs('contacts.*') ? 'mx-4 sm:mx-6 lg:mx-8 xl:mx-12 2xl:mx-16' : '' }}">
                    {{ session('error') }}
                </div>
            @endif
            
            {{ $slot }}
        </main>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>