<?php

namespace App\Http\Controllers;

use App\ProductDimensions;
use Illuminate\Http\Request;

class ProductDimensionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro_dimensions = ProductDimensions::latest()->paginate(10);
        return view('product_dimensions.index', compact('pro_dimensions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product_dimensions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        ProductDimensions::create($request->only(['name']));
        return redirect()->route('product_dimensions.index')->with('success', 'Product Dimensions created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDimensions $productDimensions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDimensions $productDimensions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDimensions $productDimensions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDimensions $productDimensions)
    {
        //
    }
}
