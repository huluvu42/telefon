// resources/views/admin/dashboard.blade.php
<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600">Verwalten Sie Kontakte und laden Sie Excel-Dateien hoch</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-medium">👥</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Gesamt Kontakte</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_contacts']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-medium">📋</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Hauptliste</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['main_contacts']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-medium">📱</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mit Mobilnummer</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['mobile_contacts']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-medium">📂</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mobile Gruppen</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['mobile_groups']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Schnellaktionen</h3>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('admin.uploads.create') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        📤 Excel-Datei hochladen
                    </a>
                    
                    <a href="{{ route('admin.contacts.index') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ✏️ Kontakte verwalten
                    </a>
                    
                    <a href="{{ route('admin.contacts.create') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ➕ Neuen Kontakt anlegen
                    </a>
                </div>
            </div>
            
            <!-- Recent Uploads -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Letzte Uploads</h3>
                </div>
                <div class="p-6">
                    @if($stats['recent_uploads']->count() > 0)
                        <div class="space-y-3">
                            @foreach($stats['recent_uploads'] as $upload)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $upload->original_filename }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $upload->uploader->name }} • {{ $upload->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $upload->type === 'main' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $upload->type === 'main' ? 'Hauptliste' : 'Mobiltelefone' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.uploads.index') }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                Alle Uploads anzeigen →
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Noch keine Uploads vorhanden.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>