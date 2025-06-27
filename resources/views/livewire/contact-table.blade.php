{{-- resources/views/livewire/contact-table.blade.php --}}
<div>
    <!-- Search Section -->
    <div class="mb-6">
        <!-- Linksbündiges Search Input -->
        <div class="w-full max-w-2xl">
            <label for="search" class="sr-only">Suche</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" id="search" 
                       class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg" 
                       placeholder="Nach Name, Telefon, E-Mail, Haus oder Abteilung suchen...">
            </div>
        </div>
    </div>
    
    <!-- Results Count -->
    <div class="mb-4 text-sm text-gray-600">
        {{ $contacts->total() }} Kontakte gefunden
    </div>
    
    <!-- Contacts Table - Responsive mit Mindestbreite für 1280px -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-300" style="min-width: 1200px; table-layout: auto;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 120px; width: 12%;">
                            Nachname
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 120px; width: 12%;">
                            Vorname
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 100px; width: 8%;">
                            Titel
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 120px; width: 12%;">
                            Telefon
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 120px; width: 12%;">
                            Mobil
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 100px; width: 8%;">
                            Fax
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 200px; width: 20%;">
                            E-Mail
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 80px; width: 6%;">
                            Haus
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="min-width: 120px; width: 15%;">
                            Abteilung
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($contacts as $index => $contact)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors duration-150">
                            {{-- Dynamische Nachname-Spalte: colspan=3 wenn kein Vorname vorhanden (überspannt Nachname+Vorname+Titel) --}}
                            <td class="px-4 py-3 text-sm font-medium text-gray-900" 
                                style="min-width: 120px; word-wrap: break-word;"
                                @if(empty(trim($contact->first_name))) colspan="3" @endif>
                                {{ $contact->name }}
                            </td>
                            
                            {{-- Vorname-Spalte: nur anzeigen wenn Vorname vorhanden --}}
                            @if(!empty(trim($contact->first_name)))
                                <td class="px-4 py-3 text-sm text-gray-900" style="min-width: 120px; word-wrap: break-word;">
                                    {{ $contact->first_name }}
                                </td>
                                
                                {{-- Titel-Spalte: nur anzeigen wenn Vorname vorhanden --}}
                                <td class="px-4 py-3 text-sm text-gray-600" style="min-width: 100px; word-wrap: break-word;" title="{{ $contact->title }}">
                                    {{ $contact->title }}
                                </td>
                            @endif
                            
                            <td class="px-4 py-3 text-sm" style="min-width: 120px;">
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-150">
                                        {{ $contact->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm" style="min-width: 120px;">
                                @if($contact->mobile)
                                    <a href="tel:{{ $contact->mobile }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-150">
                                        {{ $contact->mobile }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" style="min-width: 100px;">
                                {{ $contact->fax ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm" style="min-width: 200px; word-wrap: break-word;">
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-150"
                                       title="{{ $contact->email }}">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" style="min-width: 80px;">
                                {{ $contact->building ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" style="min-width: 120px; word-wrap: break-word;" title="{{ $contact->department }}">
                                {{ $contact->department ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center space-y-2">
                                    <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p>Keine Kontakte gefunden.</p>
                                    <p class="text-xs">Versuchen Sie eine andere Suchanfrage oder entfernen Sie die Filter.</p>
                                </div>
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
    
    <!-- Results Summary -->
    <div class="mt-4 text-sm text-gray-500 text-center">
        Zeige {{ $contacts->firstItem() ?? 0 }} bis {{ $contacts->lastItem() ?? 0 }} von {{ $contacts->total() }} Kontakten
    </div>
</div>