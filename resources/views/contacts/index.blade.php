{{-- resources/views/contacts/index.blade.php --}}
<x-app-layout>
    {{-- Container mit max-width 1920px für optimale Lesbarkeit --}}
    <div class="w-full max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">Telefonverzeichnis</h1>
                <p class="mt-1 text-sm text-gray-600">Durchsuchen Sie alle Kontakte alphabetisch</p>
            </div>
            
            {{-- Container für die Tabelle mit optimaler Breite --}}
            <div class="p-6">
                @livewire('contact-table')
            </div>
        </div>
    </div>
</x-app-layout>