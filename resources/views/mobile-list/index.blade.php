<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <!-- Header mit PDF-Button links oben -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-gray-900">Mobiltelefone</h1>
                        <p class="mt-1 text-sm text-gray-600">Verzeichnis Rufanlage und Mobiltelefone</p>
                    </div>
                    
                    <!-- PDF Button links oben -->
                    <div class="ml-4">
                        <a href="{{ route('mobile.pdf') }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm">
                            🖨️ PDF drucken
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="px-6 py-6">
                <div class="border border-gray-200 rounded-lg overflow-hidden p-4 mb-6">
                    
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Name oder Telefonnummer suchen..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent text-lg"
                           autocomplete="off">
                           
                    <p class="mt-2 text-sm text-gray-400">
                        <span id="resultCount">Alle Einträge</span> werden angezeigt. 
                        Suchen Sie direkt in der Liste - Drücken Sie ESC zum Zurücksetzen.
                    </p>
                </div>

                <!-- Results -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="divide-y divide-gray-100" id="searchResults">
                        @forelse($groups as $group)
                            @if($group->entries->count() > 0)
                                <!-- Group Section -->
                                <div class="group-section">
                                    <!-- Group Header (gelb wie in Excel) -->
                                    <div class="bg-yellow-200 border-b border-yellow-300 px-6 py-3">
                                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide group-name">{{ $group->name }}</h3>
                                        @if($group->sheet_name)
                                            <p class="text-sm text-gray-600 mt-1">{{ $group->sheet_name }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Group Entries -->
                                    @foreach($group->entries as $entry)
                                        <div class="entry-item px-6 py-4 hover:bg-gray-50 transition-colors duration-150" 
                                             data-name="{{ strtolower($entry->name) }}" 
                                             data-phone="{{ $entry->phone ?? '' }}"
                                             data-group="{{ strtolower($group->name) }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="text-lg font-medium text-gray-900 entry-name">{{ $entry->name }}</div>
                                                    <div class="text-sm text-gray-500 mt-1">{{ $group->name }}</div>
                                                </div>
                                                
                                                @if($entry->phone)
                                                    <div class="ml-4 flex items-center space-x-3">
                                                        <span class="text-lg font-mono text-gray-700 entry-phone bg-gray-100 px-3 py-1 rounded">{{ $entry->phone }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">Keine Telefonnummer</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="text-gray-400 text-6xl mb-4">📱</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Mobiltelefon-Daten gefunden</h3>
                                <p class="text-gray-600">Laden Sie zuerst eine Mobiltelefonliste hoch.</p>
                                @can('admin')
                                    <a href="{{ route('admin.uploads.create') }}" 
                                       class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        📤 Liste hochladen
                                    </a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- No Results Message -->
                    <div id="noResults" class="hidden px-6 py-12 text-center">
                        <div class="text-gray-400 text-6xl mb-4">🔍</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Ergebnisse gefunden</h3>
                        <p class="text-gray-600">Versuchen Sie es mit einem anderen Suchbegriff.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .highlight {
            background-color: #fef08a;
            font-weight: bold;
            padding: 1px 2px;
            border-radius: 2px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const noResults = document.getElementById('noResults');
            const resultCount = document.getElementById('resultCount');
            const allEntries = document.querySelectorAll('.entry-item');
            const allGroupSections = document.querySelectorAll('.group-section');
            
            let totalEntries = allEntries.length;
            
            function updateResultCount(visibleCount) {
                if (visibleCount === totalEntries) {
                    resultCount.textContent = `Alle ${totalEntries} Einträge`;
                } else {
                    resultCount.textContent = `${visibleCount} von ${totalEntries} Einträgen`;
                }
            }
            
            function highlightText(element, searchTerm) {
                if (!searchTerm) {
                    // Restore original text without highlights
                    const originalText = element.textContent;
                    element.innerHTML = originalText;
                    return;
                }
                
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                const originalText = element.textContent;
                element.innerHTML = originalText.replace(regex, '<span class="highlight">$1</span>');
            }
            
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;
                let hasVisibleGroups = false;
                
                allGroupSections.forEach(section => {
                    const entries = section.querySelectorAll('.entry-item');
                    let groupHasVisibleEntries = false;
                    
                    entries.forEach(entry => {
                        const name = entry.dataset.name;
                        const phone = entry.dataset.phone;
                        const group = entry.dataset.group;
                        
                        const matchesSearch = !searchTerm || 
                                            name.includes(searchTerm) || 
                                            phone.includes(searchTerm) ||
                                            group.includes(searchTerm);
                        
                        if (matchesSearch) {
                            entry.style.display = 'block';
                            groupHasVisibleEntries = true;
                            visibleCount++;
                            
                            // Highlight matching text
                            const nameElement = entry.querySelector('.entry-name');
                            const phoneElement = entry.querySelector('.entry-phone');
                            
                            highlightText(nameElement, searchTerm);
                            if (phoneElement) highlightText(phoneElement, searchTerm);
                        } else {
                            entry.style.display = 'none';
                        }
                    });
                    
                    // Show/hide entire group section
                    if (groupHasVisibleEntries) {
                        section.style.display = 'block';
                        hasVisibleGroups = true;
                    } else {
                        section.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (hasVisibleGroups) {
                    searchResults.style.display = 'block';
                    noResults.style.display = 'none';
                } else {
                    searchResults.style.display = 'none';
                    noResults.style.display = 'block';
                }
                
                updateResultCount(visibleCount);
            }
            
            // Real-time search
            searchInput.addEventListener('input', performSearch);
            
            // ESC key to clear search
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    performSearch();
                    searchInput.blur();
                }
            });
            
            // Focus search input on page load
            searchInput.focus();
            
            // Initialize count
            updateResultCount(totalEntries);
        });
    </script>
</x-app-layout>