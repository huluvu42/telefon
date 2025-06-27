<?php
// app/Http/Controllers/MobileListController.php
namespace App\Http\Controllers;

use App\Models\MobileGroup;
use App\Models\Upload;
use Illuminate\Http\Response;

class MobileListController extends Controller
{
    public function index()
{
    // Lade alle Gruppen mit besserer Sortierung
    $groups = MobileGroup::with(['entries' => function($query) {
        $query->orderBy('order_position');
    }])
    ->orderBy('order_position') // Wichtig: Nach order_position sortieren
    ->get();
    
    
    return view('mobile-list.index', compact('groups'));
}
    
    public function printPdf()
    {
        $upload = Upload::where('type', 'mobile')
            ->latest()
            ->first();
            
        if (!$upload) {
            abort(404, 'No mobile list PDF available');
        }
        
        $pdfPath = storage_path('app/public/mobile_lists/' . $upload->filename . '.pdf');
        
        if (!file_exists($pdfPath)) {
            abort(404, 'PDF file not found');
        }
        
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Mobiltelefonliste.pdf"'
        ]);
    }
}