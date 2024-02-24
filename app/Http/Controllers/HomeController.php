<?php

namespace App\Http\Controllers;

use App\Traits\MostDownloaded;

class HomeController extends Controller
{

    use MostDownloaded;

    public function show()
    {
        $mostDownloadedProducts = $this->MostDownloaded(3);

        return view('welcome', compact('mostDownloadedProducts'));
    }
}
