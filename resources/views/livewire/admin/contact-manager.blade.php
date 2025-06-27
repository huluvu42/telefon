// resources/views/livewire/admin/contact-manager.blade.php
<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif
    
    <!-- Search -->
    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   placeholder="Kontakte durchsuchen..." 
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>
    
    <!-- Edit Modal (Inline) -->
    @if($editingContactId)
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kontakt bearbeiten</h3>
            
            <form wire:submit.prevent="updateContact" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vorname *</label>
                    <input type="text" wire:model="first_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titel</label>
                    <input type="text" wire:model="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" wire:model="phone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobil</label>
                    <input type="text" wire:model="mobile" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fax</label>
                    <input type="text" wire:model="fax" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
                    <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Haus</label>
                    <input type="text" wire:model="building" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abteilung</label>
                    <input type="text" wire:model="department" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="md:col-span-2 flex justify-end space-x-3">
                    <button type="button" wire:click="cancelEdit" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Abbrechen
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Speichern
                    </button>
                </div>
            </form>
        </div>
    @endif
    
    <!-- Contacts Table -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontaktdaten</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quelle</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Aktionen</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $contact->first_name }} {{ $contact->name }}
                                    </div>
                                    @if($contact->title)
                                        <div class="text-sm text-gray-500">{{ $contact->title }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 space-y-1">
                                    @if($contact->phone)
                                        <div>📞 {{ $contact->phone }}</div>
                                    @endif
                                    @if($contact->mobile)
                                        <div>📱 {{ $contact->mobile }}</div>
                                    @endif
                                    @if($contact->email)
                                        <div>✉️ {{ $contact->email }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($contact->department)
                                    <div>{{ $contact->department }}</div>
                                @endif
                                @if($contact->building)
                                    <div class="text-xs">{{ $contact->building }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $contact->source === 'main' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $contact->source === 'mobile' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $contact->source === 'manual' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $contact->source === 'main' ? 'Excel Hauptliste' : '' }}
                                    {{ $contact->source === 'mobile' ? 'Excel Mobil' : '' }}
                                    {{ $contact->source === 'manual' ? 'Manuell' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editContact({{ $contact->id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        ✏️
                                    </button>
                                    <button wire:click="deleteContact({{ $contact->id }})" 
                                            onclick="return confirm('Kontakt wirklich löschen?')"
                                            class="text-red-600 hover:text-red-900">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                Keine Kontakte gefunden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>