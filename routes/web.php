<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\MobileListController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts', function () {
    return view('contacts.index');
})->name('contacts.index');

Route::prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/', [MobileListController::class, 'index'])->name('index');
    Route::get('/pdf', [MobileListController::class, 'printPdf'])->name('pdf');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('contacts', ContactController::class);
    Route::resource('uploads', UploadController::class)->only(['index', 'create', 'store']);
});

// Temporär in routes/web.php hinzufügen:
Route::get('/debug-excel', function() {
    // Lade die letzte Excel-Datei
    $upload = \App\Models\Upload::where('type', 'mobile')->latest()->first();
    
    if (!$upload) {
        return 'Keine Upload gefunden';
    }
    
    $filePath = storage_path('app/public/uploads/' . $upload->filename);
    
    if (!file_exists($filePath)) {
        return 'Datei nicht gefunden: ' . $filePath;
    }
    
    // Excel-Datei laden und analysieren
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    
    $debug = [
        'sheet_count' => $spreadsheet->getSheetCount(),
        'sheets' => []
    ];
    
    foreach ($spreadsheet->getAllSheets() as $index => $worksheet) {
        $sheetName = $worksheet->getTitle();
        $data = $worksheet->toArray();
        
        $debug['sheets'][$index] = [
            'name' => $sheetName,
            'rows' => count($data),
            'first_10_rows' => array_slice($data, 0, 10)
        ];
    }
    
    return response()->json($debug);
});

// Weitere Debug-Route:
Route::get('/debug-mobile-import', function() {
    $upload = \App\Models\Upload::where('type', 'mobile')->latest()->first();
    
    if (!$upload) {
        return 'Keine Upload gefunden';
    }
    
    $filePath = storage_path('app/public/uploads/' . $upload->filename);
    
    $service = new \App\Services\ExcelImportService();
    $debug = $service->debugMobileImport($filePath);
    
    return response()->json($debug);
});

require __DIR__.'/auth.php';
