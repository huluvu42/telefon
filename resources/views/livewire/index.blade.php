// resources/views/contacts/index.blade.php
<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">Telefonverzeichnis</h1>
                <p class="mt-1 text-sm text-gray-600">Durchsuchen Sie alle Kontakte alphabetisch</p>
            </div>
            
            <div class="p-6">
                @livewire('contact-table')
            </div>
        </div>
    </div>
</x-app-layout>