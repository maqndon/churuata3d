<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCommonContent;

class ProductCommonContentService
{
    public static function getProductCommonContent()
    {
        $commonContents = ProductCommonContent::all();

        $productCommonContent=[];

        foreach ($commonContents as $commonContent) {
            $type = $commonContent->type;
            $productCommonContent[$type] = $commonContent->content;
        }

        return $productCommonContent;
    }
}