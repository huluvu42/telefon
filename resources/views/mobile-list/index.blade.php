<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-900">Mobiltelefon-Listen</h1>
                <p class="mt-1 text-sm text-gray-600">Wählen Sie eine Liste zur Anzeige</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($sheets as $sheet)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $sheet }}</h3>
                            <p class="text-sm text-gray-500 mb-4">Rufanlagen und Mobiltelefone</p>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('mobile.show', $sheet) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    📱 Online ansehen
                                </a>
                                
                                <a href="{{ route('mobile.pdf', $sheet) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    🖨️ PDF drucken
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($sheets->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📱</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Keine Mobiltelefon-Listen verfügbar</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            Es wurden noch keine Mobiltelefon-Listen hochgeladen.
                        </p>
                        @auth
                            @can('upload_files')
                                <a href="{{ route('admin.uploads.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    📤 Erste Liste hochladen
                                </a>
                            @endcan
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>