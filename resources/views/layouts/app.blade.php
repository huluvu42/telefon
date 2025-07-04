// resources/views/layouts/app.blade.php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Telefonverzeichnis') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            @can('admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">
                                    Admin
                                </a>
                            @endcan
                            
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
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
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                        Kontakte
                    </a>
                    <a href="{{ route('mobile.index') }}" 
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-gray-900">
                        Mobiltelefone
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            {{ $slot }}
        </main>
    </div>
    
    @livewireScripts
</body>
</html>