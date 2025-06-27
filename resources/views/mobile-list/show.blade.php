<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $sheetName }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Verzeichnis Rufanlage und Mobiltelefone</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('mobile.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        ← Zurück
                    </a>
                    
                    <a href="{{ route('mobile.pdf', $sheetName) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        🖨️ PDF drucken
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Mobile: Stack columns vertically -->
                <div class="block lg:hidden space-y-8">
                    @foreach($groupedByColumn as $columnNum => $groups)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-4 py-2 border-b">
                                <h3 class="text-lg font-semibold text-gray-800">Spalte {{ $columnNum }}</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                @foreach($groups as $group)
                                    <div class="border border-gray-200 rounded-md overflow-hidden">
                                        {{-- Gelber Gruppen-Header (wie in Excel) --}}
                                        <div class="bg-yellow-200 border-b border-yellow-300 px-3 py-2">
                                            <h4 class="font-bold text-sm text-gray-900 uppercase tracking-wide">{{ $group->name }}</h4>
                                        </div>
                                        
                                        {{-- Weiße Einträge --}}
                                        @if($group->entries->count() > 0)
                                            <div class="bg-white">
                                                @foreach($group->entries as $entry)
                                                    <div class="px-3 py-2 border-b border-gray-100 last:border-b-0 flex justify-between items-center hover:bg-gray-50">
                                                        <span class="text-sm text-gray-900 flex-1 pr-2">{{ $entry->name }}</span>
                                                        @if($entry->phone)
                                                            <a href="tel:{{ $entry->phone }}" 
                                                               class="text-sm text-blue-600 hover:text-blue-800 font-mono whitespace-nowrap">
                                                                {{ $entry->phone }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="px-3 py-2 text-sm text-gray-500 italic bg-white">
                                                Keine Einträge
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Desktop: 3-column layout (exakt wie Excel) -->
                <div class="hidden lg:block">
                    <div class="grid grid-cols-3 gap-6">
                        @for($col = 1; $col <= 3; $col++)
                            <div class="space-y-1">
                                @if(isset($groupedByColumn[$col]))
                                    @foreach($groupedByColumn[$col] as $group)
                                        {{-- Gelber Header wie in Excel --}}
                                        <div class="bg-yellow-200 border border-yellow-300 px-3 py-1">
                                            <h4 class="font-bold text-xs text-gray-900 uppercase tracking-wide">{{ $group->name }}</h4>
                                        </div>
                                        
                                        {{-- Einträge in weißen Boxen --}}
                                        @if($group->entries->count() > 0)
                                            @foreach($group->entries as $entry)
                                                <div class="bg-white border-l border-r border-b border-gray-300 px-3 py-1 hover:bg-gray-50 flex items-center justify-between min-h-[28px]">
                                                    @if($entry->phone)
                                                        <span class="text-xs font-mono text-gray-900 w-12 flex-shrink-0">{{ $entry->phone }}</span>
                                                    @else
                                                        <span class="w-12 flex-shrink-0"></span>
                                                    @endif
                                                    <span class="text-xs text-gray-900 flex-1 ml-2 truncate">{{ $entry->name }}</span>
                                                    @if($entry->phone)
                                                        <a href="tel:{{ $entry->phone }}" class="ml-2 text-blue-600 hover:text-blue-800 text-xs">📞</a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                        
                                        {{-- Leerzeile nach jeder Gruppe für visuellen Abstand --}}
                                        <div class="h-2"></div>
                                    @endforeach
                                @else
                                    {{-- Leere Spalte --}}
                                    <div class="text-center py-8 text-gray-400">
                                        <div class="text-2xl mb-2">📱</div>
                                        <p class="text-xs">Spalte {{ $col }} leer</p>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
                
                {{-- Suche über alle Einträge --}}
                @if($groupedByColumn->isNotEmpty())
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Schnellsuche
                                </h3>
                                <div class="mt-2">
                                    <input type="text" 
                                           id="searchInput"
                                           placeholder="Name oder Telefonnummer suchen..."
                                           class="w-full px-3 py-2 border border-blue-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-blue-600">
                                        Suchen Sie direkt in der Liste - Drücken Sie ESC zum Zurücksetzen
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                {{-- Fallback: Keine Daten verfügbar --}}
                @if($groupedByColumn->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📱</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Daten verfügbar</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Für diese Liste sind keine Mobiltelefon-Daten vorhanden.
                        </p>
                        @auth
                            @hasrole('admin')
                                <a href="{{ route('admin.uploads.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    📤 Excel-Datei hochladen
                                </a>
                            @endhasrole
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- JavaScript für Suche --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (!searchInput) return;

            let allEntries = [];
            
            // Sammle alle Einträge für die Suche
            document.querySelectorAll('[data-searchable]').forEach(entry => {
                allEntries.push({
                    element: entry,
                    text: entry.textContent.toLowerCase(),
                    phone: entry.dataset.phone || '',
                    name: entry.dataset.name || ''
                });
            });

            // Suche implementieren
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    // Alle Einträge anzeigen
                    document.querySelectorAll('.hover\\:bg-gray-50').forEach(entry => {
                        entry.style.display = '';
                        entry.classList.remove('bg-yellow-100');
                    });
                    return;
                }

                // Durch alle Einträge filtern
                document.querySelectorAll('.hover\\:bg-gray-50').forEach(entry => {
                    const text = entry.textContent.toLowerCase();
                    
                    if (text.includes(searchTerm)) {
                        entry.style.display = '';
                        entry.classList.add('bg-yellow-100'); // Highlight gefundene Einträge
                    } else {
                        entry.style.display = 'none';
                        entry.classList.remove('bg-yellow-100');
                    }
                });
            });

            // ESC zum Zurücksetzen
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    e.target.value = '';
                    e.target.dispatchEvent(new Event('input'));
                    e.target.blur();
                }
            });
        });
    </script>
</x-app-layout>