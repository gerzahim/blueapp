<?php

namespace App\Http\Controllers;

use App\Purchases;
use App\PurchasesItem;
use App\Vendor;
use App\Product;
use App\Courier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\StockController;

class PurchaseController extends Controller
{
    /**
     * Display a listing of POs
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pos = Purchases::latest()->paginate(10);
        return view('po.index', compact('pos'));
    }

    /**
     * Get List of Products by Json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsbyAjax() {
        $products = Product::all();
        return response()->json(['products' => $products]);
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
     * Create New PO and Stock
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'                => 'required',
            'transaction_type_id' => 'required',
            'product_id.*'        => 'required|integer|min:1',
            'qty.*'               => 'required|integer|min:1'
        ]);

        $time_now     = date('Y-m-d H:i:s');


        //'courier_id','tracking','transaction_type_id','bol','package_list','reference'
        $data = array(
            'name' => $request->name,
            'contact_type_id' => 1,
            'contact_id' => $request->vendor_id,
            'courier_id' => $request->courier_id,
            'tracking' => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'bol' => $request->bol,
            'package_list' => $request->package_list,
            'reference' => $request->reference,
            'created_at'   => $time_now,
            'updated_at'   => $time_now
        );

        $purchases = Purchases::create([
            'name'                => $request->name,
            'contact_type_id'     => 1,
            'contact_id'          => $request->vendor_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'bol'                 => $request->bol,
            'package_list'        => $request->package_list,
            'reference'           => $request->reference,
            'created_at'          => $time_now,
            'updated_at'          => $time_now
        ]);

        $lastInsertedId= $purchases->id;

        if($purchases){
            $purchases_id = $lastInsertedId;
            $product_id   = $request->product_id;
            $qty          = $request->qty;
            $batch_number = $request->batch_number;


            for($count = 0; $count < count($product_id); $count++) {
                $data = array(
                    'purchases_id' => $purchases_id,
                    'product_id'   => $product_id[$count],
                    'qty'          => $qty[$count],
                    'batch_number' => $batch_number[$count],
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );

                // Insert PO Items Associated to PO
                PurchasesItem::insert($data);

                // Saving Stock
                $stock = new StockController();
                $stock->registerProductStock($purchases_id, $product_id[$count], $qty[$count]);
            }
        }
        return redirect()->route('po.index')->with('success', 'PO created successfully.');
    }

    public function updatePO(){
        // Select PO
        // Edit PO and ProductItems
        // qty
        // Update PO type po
        // Update Stock
    }

    public function storeRMA(){
        // Select Client
        // Select PO
        // Select Product
        // qty
        // Create PO type Rma
        // Update Stock
    }

    /**
     * @param Purchases $purchases
     */
    public function show(Purchases $purchases)
    {
        //
    }

    /**
     * @param Purchases $purchases
     */
    public function edit(Purchases $purchases)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Purchases $purchases
     */
    public function update(Request $request, Purchases $purchases)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param Purchases $purchases
     */
    public function destroy(Purchases $purchases)
    {
        //
    }
}
