// resources/views/admin/contacts/index.blade.php
<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Kontakte verwalten</h1>
                    <p class="mt-1 text-sm text-gray-600">Bearbeiten, löschen oder hinzufügen von Kontakten</p>
                </div>
                
                <a href="{{ route('admin.contacts.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ➕ Neuer Kontakt
                </a>
            </div>
            
            <div class="p-6">
                @livewire('admin.contact-manager')
            </div>
        </div>
    </div>
</x-app-layout>