<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index')->with('products', $products);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function create()
    {

        return view('products.create');
    }
    public function store(Request $request)
    {
      $validateData = $request->validate([
            'name' =>'required|max:255',
            'price' =>'required|numeric',
           'stock' =>'required|numeric',
        ], [
            'name.required' => 'The name field is required.',
            'price.required' => 'The price field is required.',
            'stock.required' => 'The stock field is required.'
        ]);
        $products = Product::create($validateData);
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $products = Product::find($id);
        return view('products.show')->with('product', $products);
    }

    /**
     * Update the specified resource in storage.
     */

    public function edit($id)
    {
        $products = Product::find($id);
        return view('products.edit')->with('product', $products);
    }

    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'name' =>'required|max:255',
            'price' =>'required|numeric',
           'stock' =>'required|numeric',
        ]);
        product::whereId($id)->update($validateData);
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::destroy($id);
        return view('products.index');
    }
}
