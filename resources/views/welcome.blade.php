<x-guest-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Telefonverzeichnis</span>
                                <span class="block text-blue-600 xl:inline">Online</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Moderne, durchsuchbare Telefonlisten mit Admin-Bereich für einfache Verwaltung. 
                                Importieren Sie Excel-Dateien und verwalten Sie Kontakte effizient.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('contacts.index') }}" 
                                       class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                        📞 Kontakte durchsuchen
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="{{ route('mobile.index') }}" 
                                       class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                                        📱 Mobiltelefone
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <div class="h-56 w-full bg-gradient-to-br from-blue-400 to-blue-600 sm:h-72 md:h-96 lg:w-full lg:h-full flex items-center justify-center">
                    <div class="text-white text-9xl">📞</div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Alles was Sie brauchen
                    </p>
                </div>

                <div class="mt-10">
                    <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white text-2xl">
                                🔍
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Durchsuchbar</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Suchen Sie nach Namen, Telefonnummern oder E-Mail-Adressen. Filtern Sie nach Haus und Abteilung.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white text-2xl">
                                📱
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Mobil-optimiert</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Funktioniert perfekt auf Smartphones und Tablets. Mobilspalte kann ausgeblendet werden.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white text-2xl">
                                📤
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Excel-Import</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Laden Sie Excel-Dateien hoch und alle Daten werden automatisch importiert und verarbeitet.
                            </p>
                        </div>

                        <div class="relative">
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white text-2xl">
                                🔐
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">LDAP-Integration</p>
                            <p class="mt-2 ml-16 text-base text-gray-500">
                                Nutzen Sie bestehende Active Directory Benutzer oder lokale Konten für die Anmeldung.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-50">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    <span class="block">Bereit loszulegen?</span>
                    <span class="block text-blue-600">Melden Sie sich an oder durchsuchen Sie die Kontakte.</span>
                </h2>
                <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                    <div class="inline-flex rounded-md shadow">
                        @auth
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Anmelden
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
