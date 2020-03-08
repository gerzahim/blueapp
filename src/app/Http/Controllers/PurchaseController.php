<?php

namespace App\Http\Controllers;

use App\Client;
use App\Purchases;
use App\PurchasesItem;
use App\Vendor;
use App\Product;
use App\Courier;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

use App\Http\Controllers\StockController;


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
     * Get List of Products by Json
     * @return JsonResponse
     */
    public function getProductsbyAjax() {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getClientsbyAjax() {
        $clients = Client::all();
        return response()->json(['clients' => $clients]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getVendorsbyAjax() {
        $vendors = Vendor::all();
        return response()->json(['vendors' => $vendors]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getCouriersbyAjax() {
        $couriers = Courier::all();
        return response()->json(['couriers' => $couriers]);
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

        $this->validate($request, [
            'name'                => 'required|unique:purchases|max:50',
            'transaction_type_id' => 'required',
            'vendor_id'           => 'required'
        ]);

        $time_now     = date('Y-m-d H:i:s');

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
                PurchasesItem::insert($data_po);

                // Saving Stock
                $stock = new StockController();
                $stock->registerProductStock($purchases_id, $po_line->product_id, $po_line->qty);

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

        $products_po = PurchasesItem::where('purchases_id',$id)->get();
        foreach($products_po as $product_po){
            $product_po->name = Product::where('id', $product_po->id)->first()->name;
        }
        $products_po = json_encode($products_po, true);

        $vendors = Vendor::all();
        $products = Product::all();
        $couriers = Courier::all();

        return view('purchases.edit', compact('vendors','products','couriers', 'purchase', 'products_po'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Purchases $purchases
     */
    public function update(Request $request, Purchases $purchases)
    {
        //
        dd('Te pica el culo ');
    }

    /**
     * @param Request $request
     * @param Purchases $purchases
     * @return RedirectResponse
     */
    public function destroy(Request $request, Purchases $purchases)
    {
        $purchases->id = $request->id;
        //$purchases->delete();
        Purchases::where('id',$purchases->id)->delete();
        $stock = Stock::where('purchases_id',$purchases->id)->delete();
        $purchasesItems = PurchasesItem::where('purchases_id',$purchases->id)->delete();
        //Pending// Delete Order associated to PO
        return redirect()->route('purchases.index')->with('success', 'Purchase - PO has been deleted successfully!');
    }




}
