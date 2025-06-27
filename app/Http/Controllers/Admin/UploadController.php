<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExcelImportService;
use App\Models\Upload; // ← Dieser Import fehlte!
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(private ExcelImportService $importService)
    {
    }
    
    public function index()
    {
        $uploads = Upload::latest()->with('uploader')->paginate(20);
        return view('admin.uploads.index', compact('uploads'));
    }
    
    public function create()
    {
        return view('admin.uploads.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'type' => 'required|in:main,mobile',
            'replace_existing' => 'boolean',
            'sync_mobile_data' => 'boolean'
        ]);
        
        $file = $request->file('file');
        $type = $request->input('type');
        
        try {
            if ($type === 'main') {
                $result = $this->importService->importMainList($file, auth()->id());
            } else {
                $result = $this->importService->importMobileList($file, auth()->id());
            }
            
            if ($result['success']) {
                $message = 'File uploaded successfully. ';
                
                if (isset($result['imported'])) {
                    $message .= $result['imported'] . ' records processed.';
                }
                
                if ($request->boolean('sync_mobile_data') && $type === 'mobile') {
                    $syncResult = $this->importService->syncMobileData();
                    $message .= ' ' . $syncResult['synced'] . ' mobile numbers synced.';
                }
                
                return redirect()->route('admin.uploads.index')
                    ->with('success', $message);
            } else {
                return back()->withErrors(['file' => $result['error']]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Upload failed: ' . $e->getMessage()]);
        }
    }
}