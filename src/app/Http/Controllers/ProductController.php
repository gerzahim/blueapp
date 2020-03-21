<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\ProductDimensions;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $dimensions = ProductDimensions::all();
        return view('products.create', compact('categories'), compact('dimensions'));
    }

    /**
     * * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'dimensions_id' => 'required',
            'category_id' => 'required'
        ]);

        Product::create($request->only(['name','description','dimensions_id','category_id']));

        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $categories = Category::all();
        $dimensions = ProductDimensions::all();
        return view('products.show', compact('categories','dimensions','product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $dimensions = ProductDimensions::all();
        return view('products.edit', compact('categories','dimensions','product'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:products,name,'.$product->id,
            'dimensions_id' => 'required',
            'category_id' => 'required'
        ]);
        $product->update($request->only(['name','description','dimensions_id','category_id']));
        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
