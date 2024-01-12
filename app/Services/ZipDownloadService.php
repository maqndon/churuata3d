<?php

namespace App\Services;

use ZipArchive;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ZipDownloadService
{
    public function downloadFilesInZip($files, $zipFileName, $fileType)
    {

        // Check if there are files and at least one file exists
        if (!$files) {
            return response()->json(['error' => 'No files found for the product'], 404);
        }

        // Create a new ZipArchive instance
        $zip = new ZipArchive;

        // Define the name and path of the ZIP file
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        // Open the ZIP file for creation or overwrite
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // Loop through each file associated with the product
            foreach ($files as $file) {

                $filePath = storage_path('app/public/'. $fileType .'-files/' . basename($file));

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
