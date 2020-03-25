<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductDimensions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductDimensionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $messages = [
            'name.unique'    => 'The Product Dimension is already exist.'
        ];
        $this->validate($request, [
            'name'                => 'required|unique:product_dimensions|max:50',
        ], $messages);

        ProductDimensions::create($request->only(['name']));
        return redirect()->route('product_dimensions.index')->with('success', 'Product Dimensions created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productDimensions = ProductDimensions::where('id',$id)->first();
        return view('product_dimensions.show', compact('productDimensions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductDimensions  $productDimensions
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productDimensions = ProductDimensions::where('id',$id)->first();
        return view('product_dimensions.edit', compact('productDimensions'));
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
        $rules = [
            'name' => 'required|unique:product_dimensions,name,'.$request->id,
        ];
        $messages = [
            'name.unique' => 'The Product Dimensions is already Exist.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('product_dimensions/'.$request->id.'/edit/')
                ->withErrors($validator)
                ->withInput();
        }
        $data = array(
            'name'                => $request->name
        );

        $productDimensions = ProductDimensions::find($request->id);
        $productDimensions->fill($data)->save();

        return redirect()->route('product_dimensions.index')->with('success', 'Product Dimension has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param ProductDimensions $productDimensions
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, ProductDimensions $productDimensions)
    {
        //Verify if have Order Associated
        $product = Product::where('dimensions_id', $request->id)->first();
        if($product){
            return redirect()->route('product_dimensions.index')->with('warning', 'Can Not Delete this Product Dimensions, have this Product Associated => '.$product->name);
        }

        ProductDimensions::where('id',$request->id)->delete();
        return redirect()->route('product_dimensions.index')->with('success', 'Product Dimension has been deleted successfully!');
    }
}
