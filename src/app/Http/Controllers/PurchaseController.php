<?php

namespace App\Http\Controllers;

use App\Purchases;
use App\Vendor;
use App\Product;
use App\Courier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pos = Purchases::latest()->paginate(10);
        return view('po.index', compact('pos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::all();           
        $products = Product::all();
        $couriers = Courier::all();
        return view('po.create', compact('vendors','products','couriers'));
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
            'name' => 'required',
            'courier_id' => 'required',
            'transaction_type_id' => 'required',
            'product_id.*' => 'required|integer|min:1',
            'qty_product.*' => 'required|integer|min:1'
        ]);

        dd($request->all());
        Purchases::create($request->only(['name']));
 
        return redirect()->route('po.index')->with('success', 'PO created successfully.');
    }


    function insert(Request $request)
    {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        for($count = 0; $count < count($first_name); $count++) {
            $data = array(
                'first_name' => $first_name[$count],
                'last_name'  => $last_name[$count]
            );
         $insert_data[] = $data; 
        }
  
        DynamicField::insert($insert_data);
        return response()->json([
         'success'  => 'Data Added successfully.'
        ]);
    }    

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchases  $purchases
     * @return \Illuminate\Http\Response
     */
    public function show(Purchases $purchases)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchases  $purchases
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchases $purchases)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchases  $purchases
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchases $purchases)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchases  $purchases
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchases $purchases)
    {
        //
    }
}
