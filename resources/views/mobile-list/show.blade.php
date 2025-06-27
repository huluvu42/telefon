{{-- resources/views/mobile-list/show.blade.php --}}
<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $sheetName }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Rufanlagen und Mobiltelefone</p>
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
                {{-- Mobile: Stack columns vertically --}}
                <div class="block lg:hidden space-y-8">
                    @foreach($groupedByColumn as $columnNum => $groups)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Spalte {{ $columnNum }}</h3>
                            
                            @foreach($groups as $group)
                                <div class="mb-6">
                                    {{-- Gelber Gruppen-Header (wie in Excel) --}}
                                    <div class="bg-yellow-100 border border-yellow-300 px-4 py-2 rounded-t-md">
                                        <h4 class="font-bold text-sm text-gray-900 uppercase">{{ $group->name }}</h4>
                                    </div>
                                    
                                    {{-- Weiße Einträge --}}
                                    @if($group->entries->count() > 0)
                                        <div class="border border-gray-200 border-t-0 rounded-b-md">
                                            @foreach($group->entries as $entry)
                                                <div class="px-4 py-2 border-b border-gray-100 last:border-b-0 flex justify-between">
                                                    <span class="text-sm text-gray-900">{{ $entry->name }}</span>
                                                    @if($entry->phone)
                                                        <a href="tel:{{ $entry->phone }}" class="text-sm text-blue-600 hover:text-blue-800 font-mono">
                                                            {{ $entry->phone }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                
                {{-- Desktop: 3-column layout (wie in Ihren Excel-Screenshots) --}}
                <div class="hidden lg:block">
                    <div class="grid grid-cols-3 gap-6">
                        @for($col = 1; $col <= 3; $col++)
                            <div class="space-y-4">
                                @if(isset($groupedByColumn[$col]))
                                    @foreach($groupedByColumn[$col] as $group)
                                        <div class="border border-gray-200 rounded-md overflow-hidden">
                                            {{-- Gelber Header (exakt wie Excel) --}}
                                            <div class="bg-yellow-100 border-b border-yellow-300 px-4 py-2">
                                                <h4 class="font-bold text-sm text-gray-900 uppercase">{{ $group->name }}</h4>
                                            </div>
                                            
                                            {{-- Weiße Einträge mit Telefonnummern --}}
                                            @if($group->entries->count() > 0)
                                                <div class="divide-y divide-gray-100">
                                                    @foreach($group->entries as $entry)
                                                        <div class="px-4 py-2 flex justify-between items-center hover:bg-gray-50">
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
                                                {{-- Leere Gruppe --}}
                                                <div class="px-4 py-3 text-sm text-gray-500 italic">
                                                    Keine Einträge
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Leere Spalte --}}
                                    <div class="text-center py-8 text-gray-400">
                                        <div class="text-4xl mb-2">📱</div>
                                        <p class="text-sm">Spalte {{ $col }} leer</p>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
                
                {{-- Fallback: Keine Daten verfügbar --}}
                @if($groupedByColumn->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📱</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Daten verfügbar</h3>
                        <p class="text-sm text-gray-500">
                            Für diese Liste sind keine Mobiltelefon-Daten vorhanden.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('admin.uploads.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                📤 Excel-Datei hochladen
                            </a>
                        </div>
                    </div>
                @endif
                
                {{-- Zusätzliche Info-Box (optional) --}}
                @if($groupedByColumn->isNotEmpty())
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Layout-Information
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>
                                        Diese Ansicht entspricht dem 3-Spalten-Layout Ihrer Excel-Datei. 
                                        Für die optimale Druckansicht verwenden Sie den <strong>PDF-Export</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>