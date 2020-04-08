<?php

namespace App\Http\Controllers;

use App\ContactType;
use App\RMA;
use App\RMAItems;
use App\Product;
use App\Purchases;
use App\PurchasesItem;
use App\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Debugbar;
use Illuminate\Support\Facades;

class RMAController extends Controller
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
        $rmas = RMA::latest()->paginate(10);
        return view('rma.index', compact('rmas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastOrder = RMA::select('id')->orderBy('id','desc')->first();
        if($lastOrder){
            $lastOrder = $lastOrder->id+1;
        }else{
            $lastOrder = 1;
        }

        $contact_types = ContactType::all();

        // GET Request
        //$request = Request::create('/get_clients', 'GET');
        //$clients = Route::dispatch($request);

        $ctrl    = new PurchaseController();
        $clients = $ctrl->getClientsWithOrders();

        $ctrl    = new PurchaseController();
        $vendors = $ctrl->getVendorsWithOrders();

        return view('rma.create', compact('lastOrder', 'contact_types', 'clients', 'vendors') );
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
            'name.required'      => 'The RMA is Required.',
            'date.required'      => 'Must Select a Date',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required' => 'Must Select a Customer',
            'vendor_id.required' => 'Must Select a Supplier',
        ];

        if(!$request->contact_type_id) {
            $contact_id = $request->client_id;
            $this->validate($request, [
                'name'                => 'required',
                'date'                => 'required',
                'transaction_type_id' => 'required',
                'client_id'           => 'required'
            ], $messages);
        }else{
            $contact_id = $request->vendor_id;
            $this->validate($request, [
                'name'                => 'required',
                'date'                => 'required',
                'transaction_type_id' => 'required',
                'vendor_id'           => 'required'
            ], $messages);
        }

        $time_now = date('Y-m-d H:i:s');

        $data = array(
            'name'                => $request->name,
            'contact_type_id'     => $request->contact_type_id,
            'contact_id'          => $contact_id,
            'transaction_type_id' => $request->transaction_type_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $time_now,
            'updated_at'          => $time_now
        );

        $rma = RMA::create($data);
        $lastInsertedId= $rma->id;

        if($rma){
            $rma_id = $lastInsertedId;

            $rma_lines = json_decode($request->vars);

            foreach ($rma_lines as $rma_line )
            {
                $data_rma_item = array(
                    'rma_id'       => $rma_id,
                    'product_id'   => $rma_line->product_id,
                    'qty'          => $rma_line->qty,
                    'order_id'     => $rma_line->order_id,
                    'purchases_id' => $rma_line->po_item_id,
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );
                // Insert RMA Items Associated to RMA
                RMAItems::insert($data_rma_item);

                $stock = new StockController();
                if ($request->contact_type_id) {
                    // Reduce qty from PO IF contact id = 1
                    // Add Products defective to RMA and reduce from Inventory
                    $stock->addProductRMA_Vendor($rma_line->po_item_id, $rma_line->qty);
                }else{
                    //Add Products defective to RMA from Customer Return
                    $stock->addProductRMA($rma_line->po_item_id, $rma_line->qty);
                }

            }

        }
        return redirect()->route('rma.index')->with('success', 'RMA created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RMA  $rma
     * @return \Illuminate\Http\Response
     */
    public function show(RMA $rma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RMA  $rma
     * @return \Illuminate\Http\Response
     */
    public function edit(RMA $rma)
    {
        $rma_lines  = RMAItems::where('rma_id',$rma->id)->get();
        $products_rma = [];
        foreach($rma_lines as $rma_line){

            $po_line  = PurchasesItem::where('id',$rma_line->purchases_id)->first();
            $po       = Purchases::where('id',$po_line->purchases_id)->first();
            $product  = Product::where('id', $po_line->product_id)->first();
            $stock    = Stock::where('purchases_item_id',$rma_line->purchases_id)->first();

            $products_rma[] = array(
                'order_id'     => $rma_line->order_id,
                'po_id'        => $po->id,
                'po_item_id'   => $po_line->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'batch'        => $po_line->batch_number,
                'po_name'      => $po->name,
                'available'    => $stock->available,
                'qty'          => $rma_line->qty
            );
        }
        $products_rma = json_encode($products_rma, true);

        $ctrl    = new PurchaseController();
        $clients = $ctrl->getClientsWithOrders();

        $ctrl    = new PurchaseController();
        $vendors = $ctrl->getVendorsWithOrders();

        return view('rma.edit', compact('rma', 'products_rma', 'clients', 'vendors') );
    }

    /**
     *
     * Update the specified resource in storage.
     * @param Request $request
     * @param RMA $rma
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function update(Request $request, RMA $rma)
    {
        //Debugbar::info($request);
        $messages = [
            'name.required'      => 'The RMA is Required.',
            'date.required'      => 'Must Select a Date',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required' => 'Must Select a Customer',
            'vendor_id.required' => 'Must Select a Supplier',
        ];
        if(!$request->contact_type_id) {
            //Debugbar::info($request->contact_type_id);
            //Debugbar::info('entre en Customer');
            $contact_id = $request->client_id;
            $this->validate($request, [
                'name'                => 'required',
                'date'                => 'required',
                'transaction_type_id' => 'required',
                'client_id'           => 'required'
            ], $messages);
        }else{
            //Debugbar::info($request->contact_type_id);
            //Debugbar::info('entre en Supplier');
            $contact_id = $request->vendor_id;
            $this->validate($request, [
                'name'                => 'required',
                'date'                => 'required',
                'transaction_type_id' => 'required',
                'vendor_id'           => 'required'
            ], $messages);
        }

        $time_now = date('Y-m-d H:i:s');

        $data = array(
            'name'                => $request->name,
            'contact_type_id'     => $request->contact_type_id,
            'contact_id'          => $contact_id,
            'transaction_type_id' => $request->transaction_type_id,
            'courier_id'          => $request->courier_id,
            'tracking'            => $request->tracking,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $time_now,
            'updated_at'          => $time_now
        );

        $rma->fill($data)->save();

        $stock = new StockController();
        $rma_items_updated = [];

        $rma_lines = json_decode($request->vars);

        foreach ($rma_lines as $rma_line )
        {
            $data_rma_item = array(
                'rma_id'       => $rma->id,
                'product_id'   => $rma_line->product_id,
                'qty'          => $rma_line->qty,
                'order_id'     => $rma_line->order_id,
                'purchases_id' => $rma_line->po_item_id,
                'created_at'   => $time_now,
                'updated_at'   => $time_now
            );

            // If Exist
            $rma_line_previous  = RMAItems::where('rma_id',$rma->id)->where('purchases_id',$rma_line->po_item_id)->first();

            if ($rma_line_previous){
                // IF Exist
                $stock->adjustProductRMA($rma_line_previous->purchases_id, $rma_line_previous->qty, $rma_line->qty, $request->contact_type_id);

                // Update RMAItems Values
                $rma_line_previous->fill($data_rma_item)->save();
                $rma_items_updated[] = $rma_line_previous->id;
            }else{
                // Insert RMA Items if New

                $new_rma_item = RMAItems::create($data_rma_item);
                $lastInsertedId = $new_rma_item->id;
                $rma_items_updated[] = $lastInsertedId;

                if ($request->contact_type_id) {
                    // Reduce qty from PO IF contact id = 1
                    // Add Products defective to RMA and reduce from Inventory
                    $stock->addProductRMA_Vendor($rma_line->po_item_id, $rma_line->qty);
                }else{
                    //Add Products defective to RMA from Customer Return
                    $stock->addProductRMA($rma_line->po_item_id, $rma_line->qty);
                }
            }

            // Update RMA for Items_no_longer_used
            $rma_items_no_longer_used = RMAItems::where('rma_id', $rma->id)->whereNotIn('id', $rma_items_updated)->get();
            foreach ($rma_items_no_longer_used as $rma_item_no_longer_used){
                $stock->reverseProductRMA($rma_item_no_longer_used->purchases_id, $rma_item_no_longer_used->qty, $request->contact_type_id);
            }

            // Delete OrderItems No_longer_used
            RMAItems::where('rma_id', $rma->id)->whereNotIn('id', $rma_items_updated)->delete();

        }
        return redirect()->route('rma.index')->with('success', 'RMA updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     * @param RMA $rma
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(RMA $rma)
    {
        //Get All Order Lines associated to the Order
        $RMAItems = RMAItems::where('rma_id',$rma->id)->get();
        foreach ($RMAItems as $RMAItem){
            // Updating Stock
            $stock = new StockController();
            $stock->reverseProductRMA($RMAItem->purchases_id, $RMAItem->qty, $rma->contact_type_id);
        }
        // Delete Order Lines
        RMAItems::where('rma_id',$rma->id)->delete();

        //Delete Order
        $rma->delete();

        return redirect()->route('rma.index')->with('success', 'RMA has been deleted successfully!');
    }

    /**
     * reduceRmaRefurbished
     * @param $purchases_item_id
     * @param $qty
     */
    public function reduceRmaRefurbished($purchases_item_id, $qty)
    {
        $RMAItem = RMAItems::where('purchases_id', $purchases_item_id)->first();
        $RMAItem->qty = ($RMAItem->qty - $qty);
        $RMAItem->save();
    }
    /**
     * reduceRmaRefurbished
     * @param $purchases_item_id
     * @param $qty
     */
    public function addRmaRefurbished($purchases_item_id, $qty)
    {
        $RMAItem = RMAItems::where('purchases_id', $purchases_item_id)->first();
        $RMAItem->qty = ($RMAItem->qty + $qty);
        $RMAItem->save();
    }
}
