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
        $sheets = MobileGroup::select('sheet_name')
            ->distinct()
            ->orderBy('sheet_name')
            ->pluck('sheet_name');
            
        return view('mobile-list.index', compact('sheets'));
    }
    
    public function show(string $sheetName)
    {
        $groups = MobileGroup::where('sheet_name', $sheetName)
            ->with(['entries' => function($query) {
                $query->orderBy('order_position');
            }])
            ->orderBy('column_position')
            ->orderBy('order_position')
            ->get();
            
        $groupedByColumn = $groups->groupBy('column_position');
        
        return view('mobile-list.show', compact('groupedByColumn', 'sheetName'));
    }
    
    public function printPdf(string $sheetName)
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
            'Content-Disposition' => 'inline; filename="' . $sheetName . '.pdf"'
        ]);
    }
}
