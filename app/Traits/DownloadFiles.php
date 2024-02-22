<?php

namespace App\Traits;

trait DownloadFiles
{
    public function downloadFiles($slug)
    {

        $fileNames = $this->productService->getFileNames($slug);

        // Set the zip name
        $zipFileName = 'files_' . $slug . '.zip';

        // Use the service to download files in a zip
        return $this->zipDownloadService->downloadFilesInZip($fileNames, $zipFileName, $slug, 'product');
    }
}