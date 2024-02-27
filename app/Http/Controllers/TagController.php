<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    public function index()
    {
        $data = $this->loadCommonData();
        $data['tags'] = Tag::all();
        $data['mostDownloadedProductsTag'] = $data['mostDownloadedProducts']->first()->tags->first()->slug;

        return view('tags.index', $data);
    }
    
    public function show(Request $request, $slug)
    {
        return $this->showCommon($request, Tag::class, $slug, 'tags');
    }
}
