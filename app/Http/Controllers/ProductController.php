<?php

namespace App\Http\Controllers;

use App\DataTables\ProductsDataTable;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.products.list');
    }

    public function store(Request $request)
    {
        $request->validate([

        ]);

        return Product::create($request->validated());
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([

        ]);

        $product->update($request->validated());

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json();
    }
}
