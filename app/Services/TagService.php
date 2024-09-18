<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Product;

class TagService
{
    public function storeTag(array $data)
    {
        $tag = new Tag();
        $tag->name = $data['name'];
        $tag->slug = $data['slug'];
        $tag->save();

        return $tag;
    }

    public function updateTag(Tag $tag, array $data)
    {
        // Filter the data that has changed
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        // Update the tag with the filtered data
        $tag->update($data);

        return $tag;
    }

    public function destroyTag(Tag $tag)
    {
        $tag->delete();

        return true;
    }

    public function attachTag(Product $product, Tag $tag)
    {
        $product->tags()->attach($tag);

        return true;
    }

    public function detachTag(Product $product, Tag $tag)
    {
        $product->tags()->detach($tag);

        return true;
    }
}
