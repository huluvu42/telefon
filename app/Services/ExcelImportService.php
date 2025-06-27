<?php

// app/Services/ExcelImportService.php
namespace App\Services;

use App\Models\Contact;
use App\Models\MobileGroup;
use App\Models\MobileEntry;
use App\Models\Upload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class ExcelImportService
{
    public function importMainList(UploadedFile $file, int $userId): array
    {
        $upload = $this->createUploadRecord($file, 'main', $userId);
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
            
            // Find header row
            $headerRow = -1;
            foreach ($data as $index => $row) {
                if ($row[0] === 'NAME') {
                    $headerRow = $index;
                    break;
                }
            }
            
            if ($headerRow === -1) {
                throw new \Exception('Header row with "NAME" not found');
            }
            
            // Clear existing main contacts
            Contact::where('source', 'main')->delete();
            
            $imported = 0;
            $errors = [];
            
            // Process data rows
            for ($i = $headerRow + 1; $i < count($data); $i++) {
                $row = $data[$i];
                
                if (empty($row[0]) && empty($row[1])) continue; // Skip empty rows
                
                try {
                    Contact::create([
                        'name' => $row[0] ?? '',
                        'first_name' => $row[1] ?? '',
                        'title' => $row[2] ?? null,
                        'phone' => $row[3] ?? null,
                        'mobile' => $row[4] ?? null,
                        'fax' => $row[5] ?? null,
                        'email' => $row[6] ?? null,
                        'building' => $row[7] ?? null,
                        'department' => $row[8] ?? null,
                        'source' => 'main'
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$i}: " . $e->getMessage();
                }
            }
            
            $upload->update([
                'records_processed' => $imported,
                'processing_log' => ['errors' => $errors, 'imported' => $imported]
            ]);
            
            return ['success' => true, 'imported' => $imported, 'errors' => $errors];
            
        } catch (\Exception $e) {
            $upload->update([
                'processing_log' => ['error' => $e->getMessage()]
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function importMobileList(UploadedFile $file, int $userId): array
    {
        $upload = $this->createUploadRecord($file, 'mobile', $userId);
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $results = [];
            
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheetName = $worksheet->getTitle();
                $data = $worksheet->toArray();
                
                // Clear existing data for this sheet
                MobileGroup::where('sheet_name', $sheetName)->delete();
                
                $result = $this->processMobileSheet($data, $sheetName);
                $results[$sheetName] = $result;
            }
            
            // Generate PDF for print view
            $this->generateMobilePdf($file, $upload);
            
            return ['success' => true, 'sheets' => $results];
            
        } catch (\Exception $e) {
            $upload->update([
                'processing_log' => ['error' => $e->getMessage()]
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function processMobileSheet(array $data, string $sheetName): array
    {
        $groups = [];
        $imported = 0;
        
        // Process 3 columns (positions 1, 4, 7 for data)
        $columnPositions = [
            1 => [0, 1, 2], // Column 1: positions 0,1,2
            2 => [3, 4, 5], // Column 2: positions 3,4,5  
            3 => [6, 7, 8]  // Column 3: positions 6,7,8
        ];
        
        foreach ($columnPositions as $columnNum => $positions) {
            $currentGroup = null;
            $orderPosition = 0;
            
            foreach ($data as $rowIndex => $row) {
                if ($rowIndex < 2) continue; // Skip header rows
                
                $phone = $row[$positions[0]] ?? null;
                $name = $row[$positions[1]] ?? null;
                
                if (empty($name)) continue;
                
                // Check if this is a group header (all caps, no phone number)
                if (is_string($name) && $name === strtoupper($name) && empty($phone)) {
                    $currentGroup = MobileGroup::create([
                        'name' => $name,
                        'sheet_name' => $sheetName,
                        'column_position' => $columnNum,
                        'order_position' => $orderPosition++
                    ]);
                    $groups[] = $currentGroup->name;
                } elseif ($currentGroup && !empty($name)) {
                    // Regular entry
                    MobileEntry::create([
                        'group_id' => $currentGroup->id,
                        'phone' => $phone,
                        'name' => $name,
                        'order_position' => $orderPosition++
                    ]);
                    $imported++;
                }
            }
        }
        
        return ['groups' => count($groups), 'entries' => $imported];
    }
    
    private function generateMobilePdf(UploadedFile $file, Upload $upload): void
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $writer = new Mpdf($spreadsheet);
        
        $pdfPath = storage_path('app/public/mobile_lists/' . $upload->filename . '.pdf');
        
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }
        
        $writer->save($pdfPath);
        
        $upload->update([
            'processing_log' => array_merge($upload->processing_log ?? [], ['pdf_generated' => true])
        ]);
    }
    
    private function createUploadRecord(UploadedFile $file, string $type, int $userId): Upload
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename, 'public');
        
        return Upload::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'type' => $type,
            'path' => $path,
            'uploaded_by' => $userId
        ]);
    }
    
    public function syncMobileData(): array
    {
        $synced = 0;
        $mobileEntries = MobileEntry::with('group')->get();
        
        foreach ($mobileEntries as $entry) {
            // Try to match by name
            $nameParts = explode(',', $entry->name);
            if (count($nameParts) >= 2) {
                $lastName = trim($nameParts[0]);
                $firstName = trim(explode('(', $nameParts[1])[0]); // Remove parentheses content
                
                $contact = Contact::where('name', 'ilike', "%{$lastName}%")
                                ->where('first_name', 'ilike', "%{$firstName}%")
                                ->first();
                
                if ($contact && empty($contact->mobile)) {
                    $contact->update(['mobile' => $entry->phone]);
                    $synced++;
                }
            }
        }
        
        return ['synced' => $synced];
    }
}
