// resources/views/livewire/contact-table.blade.php
<div>
    <!-- Search and Filters -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Suchen</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Name, Telefon, E-Mail suchen..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="sm:w-48">
                <select wire:model.live="buildingFilter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Häuser</option>
                    @foreach($buildings as $building)
                        <option value="{{ $building }}">{{ $building }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="sm:w-48">
                <select wire:model.live="departmentFilter" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alle Abteilungen</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <button wire:click="toggleMobileColumn" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $showMobile ? 'Mobil ausblenden' : 'Mobil einblenden' }}
            </button>
            
            <div class="text-sm text-gray-500">
                {{ $contacts->total() }} Kontakte gefunden
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vorname</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefon</th>
                        @if($showMobile)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobil</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fax</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-Mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Haus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Abteilung</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $contact->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contact->first_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $contact->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->phone }}
                                    </a>
                                @endif
                            </td>
                            @if($showMobile)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($contact->mobile)
                                        <a href="tel:{{ $contact->mobile }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $contact->mobile }}
                                        </a>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contact->fax }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $contact->email }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $contact->building }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $contact->department }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $showMobile ? '9' : '8' }}" class="px-6 py-12 text-center text-sm text-gray-500">
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