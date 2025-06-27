<x-app-layout>
    <div class="px-4 sm:px-0">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">Excel-Datei hochladen</h1>
                    <p class="mt-1 text-sm text-gray-600">Laden Sie eine neue Telefonliste oder Mobiltelefon-Liste hoch</p>
                </div>
                
                <form action="{{ route('admin.uploads.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            Excel-Datei auswählen
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Datei auswählen</span>
                                        <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls" required>
                                    </label>
                                    <p class="pl-1">oder per Drag & Drop</p>
                                </div>
                                <p class="text-xs text-gray-500">Excel-Dateien bis zu 10MB</p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- File Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Dateityp
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="type_main" name="type" type="radio" value="main" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" required>
                                <label for="type_main" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">Telefonverzeichnis alphabetisch</span>
                                    <span class="block text-sm text-gray-500">Hauptliste mit allen Kontaktdaten (Name, Vorname, Titel, etc.)</span>
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="type_mobile" name="type" type="radio" value="mobile" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" required>
                                <label for="type_mobile" class="ml-3">
                                    <span class="block text-sm font-medium text-gray-700">Mobiltelefon-Liste</span>
                                    <span class="block text-sm text-gray-500">3-Spalten-Layout mit Rufanlagen und Mobiltelefonen</span>
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Options -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="replace_existing" name="replace_existing" type="checkbox" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="replace_existing" class="ml-3 text-sm text-gray-700">
                                Bestehende Daten ersetzen
                                <span class="text-gray-500">(Empfohlen: Alle vorhandenen Einträge werden durch die neue Datei ersetzt)</span>
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="sync_mobile_data" name="sync_mobile_data" type="checkbox" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="sync_mobile_data" class="ml-3 text-sm text-gray-700">
                                Mobilnummern automatisch abgleichen
                                <span class="text-gray-500">(Nur bei Mobiltelefon-Listen: Versucht Mobilnummern der Hauptliste zuzuordnen)</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Abbrechen
                        </a>
                        
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            📤 Hochladen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>