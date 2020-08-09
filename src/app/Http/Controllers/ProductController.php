<?php

namespace App\Http\Controllers;

use App\OrderItems;
use App\Product;
use App\Category;
use App\ProductDimensions;
use App\Purchases;
use App\PurchasesItem;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sortedByName()
    {
        // Sort By Name
        $products = Product::orderBy('name')->paginate(10);
        // Append sort to pagination links
        $links = $products ->appends(['sort' => 'name'])->links();
        return view('products.sorted', compact('products', 'links'));
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

        if($request->ajax()){
            return response()->json(['success'=>'Product saved successfully.']);
        }
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
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        //Verify if have Order Associated
        $purchase_item_associated = PurchasesItem::where('product_id', $product->id)->first();

        if($purchase_item_associated){
            $purchase_associated = Purchases::where('id', $purchase_item_associated->purchases_id)->first();
            return redirect()->route('product.index')->with('warning', 'Can Not Delete this Product, have this PO Associated : '.$purchase_associated->name);
        }

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product has been deleted successfully!');
    }
}
