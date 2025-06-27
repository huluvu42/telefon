<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\MobileGroup;
use App\Models\MobileEntry;
use App\Models\Upload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
            
            Log::info('Main list import started', ['rows' => count($data)]);
            
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
            
            Log::info('Header found at row', ['row' => $headerRow]);
            
            // Clear existing main contacts
            Contact::where('source', 'main')->delete();
            
            $imported = 0;
            $errors = [];
            
            // Process data rows
            for ($i = $headerRow + 1; $i < count($data); $i++) {
                $row = $data[$i];
                
                if (empty($row[0]) && empty($row[1])) continue;
                
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
            
            Log::info('Main list import completed', ['imported' => $imported, 'errors' => count($errors)]);
            
            return ['success' => true, 'imported' => $imported, 'errors' => $errors];
            
        } catch (\Exception $e) {
            Log::error('Main list import failed', ['error' => $e->getMessage()]);
            
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
            
            Log::info('Mobile list import started', ['sheets' => count($spreadsheet->getWorksheetIterator())]);
            
            foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
                $sheetName = $worksheet->getTitle();
                $data = $worksheet->toArray();
                
                Log::info('Processing sheet', ['sheet' => $sheetName, 'rows' => count($data)]);
                
                // Debug: Log first 5 rows
                for($i = 0; $i < min(5, count($data)); $i++) {
                    Log::debug("Sheet {$sheetName} Row {$i}", ['data' => $data[$i]]);
                }
                
                // Clear existing data for this sheet
                MobileGroup::where('sheet_name', $sheetName)->delete();
                
                $result = $this->processMobileSheet($data, $sheetName);
                $results[$sheetName] = $result;
                
                Log::info('Sheet processed', ['sheet' => $sheetName, 'result' => $result]);
            }
            
            // Generate PDF for print view
            $this->generateMobilePdf($file, $upload);
            
            $upload->update([
                'records_processed' => array_sum(array_column($results, 'entries')),
                'processing_log' => ['sheets' => $results]
            ]);
            
            return ['success' => true, 'sheets' => $results];
            
        } catch (\Exception $e) {
            Log::error('Mobile list import failed', ['error' => $e->getMessage()]);
            
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
        
        // Process 3 columns: 0-2, 3-5, 6-8
        $columnPositions = [
            1 => [0, 1, 2], // Column 1: positions 0,1,2
            2 => [3, 4, 5], // Column 2: positions 3,4,5  
            3 => [6, 7, 8]  // Column 3: positions 6,7,8
        ];
        
        foreach ($columnPositions as $columnNum => $positions) {
            $currentGroup = null;
            $orderPosition = 0;
            
            Log::debug("Processing column {$columnNum}", ['positions' => $positions]);
            
            foreach ($data as $rowIndex => $row) {
                if ($rowIndex < 2) continue; // Skip header rows
                
                $phone = $row[$positions[0]] ?? null;
                $name = $row[$positions[1]] ?? null;
                
                if (empty($name)) continue;
                
                Log::debug("Row {$rowIndex} Col {$columnNum}", [
                    'phone' => $phone, 
                    'name' => $name, 
                    'is_header' => is_string($name) && $name === strtoupper($name) && empty($phone)
                ]);
                
                // Check if this is a group header (all caps, no phone number)
                if (is_string($name) && $name === strtoupper($name) && empty($phone)) {
                    $currentGroup = MobileGroup::create([
                        'name' => $name,
                        'sheet_name' => $sheetName,
                        'column_position' => $columnNum,
                        'order_position' => $orderPosition++
                    ]);
                    $groups[] = $currentGroup->name;
                    
                    Log::info('Group created', ['name' => $name, 'column' => $columnNum]);
                    
                } elseif ($currentGroup && !empty($name)) {
                    // Regular entry
                    MobileEntry::create([
                        'group_id' => $currentGroup->id,
                        'phone' => $phone,
                        'name' => $name,
                        'order_position' => $orderPosition++
                    ]);
                    $imported++;
                    
                    Log::debug('Entry created', ['group' => $currentGroup->name, 'name' => $name, 'phone' => $phone]);
                }
            }
        }
        
        return ['groups' => count($groups), 'entries' => $imported];
    }
    
    private function generateMobilePdf(UploadedFile $file, Upload $upload): void
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            
            // Set proper PDF settings for multiple pages
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
            $writer->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $writer->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            
            $pdfPath = storage_path('app/public/mobile_lists/' . $upload->filename . '.pdf');
            
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }
            
            $writer->save($pdfPath);
            
            Log::info('PDF generated', ['path' => $pdfPath]);
            
            $upload->update([
                'processing_log' => array_merge($upload->processing_log ?? [], ['pdf_generated' => true])
            ]);
        } catch (\Exception $e) {
            Log::error('PDF generation failed', ['error' => $e->getMessage()]);
            
            $upload->update([
                'processing_log' => array_merge($upload->processing_log ?? [], ['pdf_error' => $e->getMessage()])
            ]);
        }
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
            $nameParts = explode(',', $entry->name);
            if (count($nameParts) >= 2) {
                $lastName = trim($nameParts[0]);
                $firstName = trim(explode('(', $nameParts[1])[0]);
                
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