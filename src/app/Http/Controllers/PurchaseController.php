<?php

namespace App\Http\Controllers;

use App\Client;
use App\OrderItems;
use App\Purchases;
use App\PurchasesItem;
use App\Vendor;
use App\Product;
use App\Courier;
use App\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use MongoDB\Driver\Session;


class PurchaseController extends Controller
{

    /**
     * Display a listing of purchases
     * @return Factory|View
     */
    public function index()
    {
        $purchases = Purchases::latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vendors = Vendor::all();
        $products = Product::all();
        $couriers = Courier::all();

        return view('purchases.create', compact('vendors','products','couriers'));
    }


    /**
     * Create New PO and Stock
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {

        $messages = [
            'name.unique'    => 'The PO Name attribute has already been taken.',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'vendor_id.required' => 'Must Select a Supplier',
        ];

        $this->validate($request, [
            'name'                => 'required|unique:purchases|max:50',
            'transaction_type_id' => 'required',
            'vendor_id'           => 'required'
        ], $messages);

        $time_now     = date('Y-m-d H:i:s');

        $data = array(
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

            $po_lines = json_decode($request->vars);
            foreach ($po_lines as $po_line )
            {
                $data_po = array(
                    'purchases_id' => $purchases_id,
                    'product_id'   => $po_line->product_id,
                    'qty'          => $po_line->qty,
                    'batch_number' => $po_line->batch_number,
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );
                // Insert PO Items Associated to PO
                $purchases_item = PurchasesItem::create($data_po);
                $lastInsertedId = $purchases_item->id;
                $purchases_item_id = $lastInsertedId;

                // Saving Stock
                $stock = new StockController();
                $stock->registerProductStock($purchases_id, $purchases_item_id, $po_line->product_id, $po_line->qty);

            }

        }
        return redirect()->route('purchases.index')->with('success', 'PO created successfully.');
    }

    public function updateRMAPO(){
        // Select PO
        // Edit PO and ProductItems
        // qty
        // Update PO type po
        // Update Stock

        //public function updatePO(Request $request, Purchases $purchases, $id){
            // Select PO
            // Edit PO and ProductItems
            // qty
            // Update PO type po
            // Update Stock
    /*
            $this->validate($request, [
                'name'                => 'required|unique:po|max:50',
                'email'               => "required|email|unique:db_users,email,$id",
                'address'             => 'required|string|min:10|unique:clients,address,'.$id,
                $address  = 'required|string|min:10|unique:clients,address,'.$this->id;
                'transaction_type_id' => 'required',
                'vendor_id'           => 'required'
            ]);

            // add and update same method
            public function rules()
            {
            if($this->method() == 'POST')
                $address = 'required|string|min:10|unique:clients,address';
            else
                $address  = 'required|string|min:10|unique:clients,address,'.$this->id;
            //put a hidden input field named id with value on your edit view and catch it here;
                return [
                'nameEN'   => 'required|string',
                'nameHE'   => 'required|string',
                'address'  => $address
                ];
            }

            $user = User::findOrFail($id);
            $user->name = trim($request->name);
            $user->email = trim($request->email);
            $user->save();

            Session::flash('success','USer information was successfully updated.');

            return redirect()->route('users.show',$user->id);
            */
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
     * @param Request $request
     * @param Purchases $purchases
     */
    public function edit(Request $request, Purchases $purchases)
    {
        dd($request);
    }

    public function editPurchase($id)
    {
        $purchase = Purchases::where('id',$id)->first();

        $po_lines  = PurchasesItem::where('purchases_id',$id)->get();
        $products_pos = [];

        foreach($po_lines as $po_line){
            $po_line->name  = Product::where('id', $po_line->product_id)->first()->name;
            $products_pos[] = array('product_id' => $po_line->product_id, 'product_name' => $po_line->name, 'qty' => $po_line->qty, 'batch_number' => $po_line->batch_number);
        }

        $products_po = json_encode($products_pos, true);

        $vendors  = Vendor::all();
        $products = Product::all();
        $couriers = Courier::all();

        return view('purchases.edit', compact('vendors','products','couriers', 'purchase', 'products_po'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Purchases $purchases
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Purchases $purchases)
    {
        $rules = [
            'name'                => 'required|max:50|unique:purchases,name,'.$request->id,
            'transaction_type_id' => 'required',
            'vendor_id'           => 'required'
        ];
        $messages = [
            'name.unique'                     => 'The PO Name attribute has already been taken.',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'vendor_id.required'              => 'Must Select a Supplier',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('editPurchase/'.$request->id)
                ->withErrors($validator)
                ->withInput();
        }


        $time_now     = date('Y-m-d H:i:s');

        $data = array(
            'name'                => $request->name,
            'contact_type_id'     => 1,
            'contact_id'          => $request->vendor_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'bol'                 => $request->bol,
            'package_list'        => $request->package_list,
            'reference'           => $request->reference,
            'updated_at'          => $time_now
        );

        $purchases = Purchases::find($request->id);
        $purchases->fill($data)->save();

        $po_lines = json_decode($request->vars);

        PurchasesItem::where('purchases_id',$request->id)->delete();
        Stock::where('purchases_id',$request->id)->delete();

        foreach ($po_lines as $po_line )
        {
            $data_po = array(
                'purchases_id' => $request->id,
                'product_id'   => $po_line->product_id,
                'qty'          => $po_line->qty,
                'batch_number' => $po_line->batch_number,
                'created_at'   => $time_now,
                'updated_at'   => $time_now
            );

            // Insert PO Items Associated to PO
            $purchases_item    = PurchasesItem::create($data_po);
            $lastInsertedId    = $purchases_item->id;
            $purchases_item_id = $lastInsertedId;

            // Saving Stock
            $stock = new StockController();
            $stock->registerProductStock($request->id, $purchases_item_id, $po_line->product_id, $po_line->qty);
        }

        return redirect()->route('purchases.index')->with('success', 'PO updated successfully.');
    }

    /**
     * @param Request $request
     * @param Purchases $purchases
     * @return RedirectResponse
     */
    public function destroy(Request $request, Purchases $purchases)
    {
        $purchases->id      = $request->id;
        $purchases_items    = PurchasesItem::where('purchases_id',$purchases->id)->get();
        $purchases_items_id = [];
        foreach ( $purchases_items as $purchase_item) {
            $purchases_items_id[] = $purchase_item->id;
        }
        //Verify if have Order Associated
        $order_items = OrderItems::where('purchases_id', $purchases_items_id)->first();
        if($order_items){
            return redirect()->route('purchases.index')->with('warning', 'Can Not Delete this PO, have Orders Associated');
        }

        Purchases::where('id',$purchases->id)->delete();
        Stock::where('purchases_id',$purchases->id)->delete();
        PurchasesItem::where('purchases_id',$purchases->id)->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase - PO has been deleted successfully!');
    }

    /**
     * Get List of Products by Json
     * @return JsonResponse
     */
    public function getProductsbyAjax() {
        $products = Product::all();
        $sorted   = $products->sortBy('name');
        $products = $sorted->values()->all();
        return response()->json(['products' => $products]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getClientsDT() {
        $clients  = Client::all();
        $subset = $clients->map->only(['id','name']);
        return response()->json(['data' => $subset]);
    }


    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getClientsbyAjax() {
        $clients  = Client::all();
        $sorted   = $clients->sortBy('name');
        $clients  = $sorted->values()->all();
        return response()->json(['clients' => $clients]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getVendorsbyAjax() {
        $vendors = Vendor::all();
        $sorted  = $vendors->sortBy('name');
        $vendors = $sorted->values()->all();
        return response()->json(['vendors' => $vendors]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getCouriersbyAjax() {

        $courier_na = Courier::where('name', 'N/A')->get();

        $couriers = Courier::whereNotIn('name', ['N/A'])->get();
        $sorted   = $couriers->sortBy('name');
        $couriers = $sorted->values()->all();

        // Merge Both Collection $merged = $original->merge($latest);
        $merged = $courier_na->merge($couriers);

        return response()->json(['couriers' => $merged]);
    }




}
