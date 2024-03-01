<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    public function index()
    {
        $data = $this->loadCommonData();
        $this->loadTagData($data);
        
        return view('tags.index', $data);
    }
    
    public function show(Request $request, string $labelSlug)
    {
        return $this->showCommon($request, Tag::class, $labelSlug, 'tags');
    }
}
