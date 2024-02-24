<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DownloadFiles
{
    public function downloadFiles(Request $request)
    {

        $slug = $request->segment(3);

        $fileNames = $this->productService->getFileNames($slug);

        // Set the zip name
        $zipFileName = 'files_' . $slug . '.zip';

        // Use the service to download files in a zip
        return $this->zipDownloadService->downloadFilesInZip($fileNames, $zipFileName, $slug, 'product');
    }
}