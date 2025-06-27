<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Upload-Historie</h1>
                    <p class="mt-1 text-sm text-gray-600">Übersicht aller hochgeladenen Excel-Dateien</p>
                </div>
                
                <a href="{{ route('admin.uploads.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    📤 Neue Datei hochladen
                </a>
            </div>
            
            <div class="p-6">
                @if($uploads->count() > 0)
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datei</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verarbeitet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hochgeladen von</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($uploads as $upload)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $upload->original_filename }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ number_format(filesize(storage_path('app/public/' . $upload->path)) / 1024, 1) }} KB
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $upload->type === 'main' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $upload->type === 'main' ? 'Hauptliste' : 'Mobiltelefone' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($upload->records_processed) }} Datensätze
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $upload->uploader->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $upload->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(isset($upload->processing_log['error']))
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Fehler
                                                </span>
                                            @elseif(isset($upload->processing_log['errors']) && count($upload->processing_log['errors']) > 0)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Warnungen
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Erfolgreich
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($uploads->hasPages())
                        <div class="mt-6">
                            {{ $uploads->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📤</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Noch keine Uploads</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Laden Sie Ihre erste Excel-Datei hoch, um zu beginnen.
                        </p>
                        <a href="{{ route('admin.uploads.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            📤 Erste Datei hochladen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>