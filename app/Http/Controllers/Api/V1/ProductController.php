<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\V1\ProductResource;

class ProductController extends Controller
{
    //
    public function index()
    {
        // ?include=author: eager load the authors in one extra query
        if ($this->include('author')) {
            return ProductResource::collection(Product::with('author')->paginate());
        }

        return ProductResource::collection(Product::paginate());
    }

    
    public function show(Product $product)
    {
        // ?include=author: load the author of this product
        if ($this->include('author')) {
            return new ProductResource($product->load('author'));
        }

        return new ProductResource($product);
    }
}
