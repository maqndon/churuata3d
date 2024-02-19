<?php

namespace App\Services;

use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ZipDownloadService
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function downloadFilesInZip($files, $zipFileName, $subdir, $fileType)
    {

        // Check if there are files and at least one file exists
        if (!$files) {
            return response()->json(['error' => 'No files found for the product'], 404);
        }

        // Create a new ZipArchive instance
        $zip = new ZipArchive;

        //local, public, etc
        $disk = basename(Storage::disk('local')->path(''));

        // Define the name and path of the ZIP file
        $zipFilePath = storage_path($disk . DIRECTORY_SEPARATOR . $zipFileName);


        // Open the ZIP file for creation or overwrite
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Loop through each file associated with the product
            foreach ($files as $file) {

                $filePath = storage_path($disk . DIRECTORY_SEPARATOR . $fileType .'-files'  . DIRECTORY_SEPARATOR . $subdir  . DIRECTORY_SEPARATOR . basename($file));

                // Check if the file exists before adding it to the ZIP
                if (File::exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                } else {
                    $zip->close();
                    return response()->json(['error' => 'File not found: ' . $filePath], 404);
                }
            }
            // Close the ZIP file
            $zip->close();

            // Check if the ZIP file was created successfully
            if ($zip->status === 0) {
                // Increase downloads by 1
                $this->productService->setDownload($subdir);
                // Download the ZIP file and delete it after sending
                return response()->download($zipFilePath)->deleteFileAfterSend();
            } else {
                return response()->json(['error' => 'ZIP file not created. ZipArchive status: ' . $zip->status], 500);
            }
        } else {
            // Handle the case where the ZIP file couldn't be opened
            return response()->json(['error' => 'Unable to open ZIP file'], 500);
        }
    }
}
