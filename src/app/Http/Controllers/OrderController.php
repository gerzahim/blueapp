<?php

namespace App\Http\Controllers;

use App\Client;
use App\Courier;
use App\Order;
use App\OrderItems;
use App\Product;
use App\Purchases;
use App\PurchasesItem;
use App\Stock;
use App\Vendor;
use Carbon\Carbon;
use Debugbar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orders = Order::latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastOrder = Order::select('id')->orderBy('id','desc')->first();
        if($lastOrder){
            //Debugbar::info($lastOrder);
            $lastOrder = $lastOrder->id+1;
        }else{
            $lastOrder = 1;
        }


        $clients = Client::all();
        $products = Product::all();
        $couriers = Courier::all();

        return view('orders.create', compact('lastOrder','clients','products','couriers') );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required'    => 'The Orden Number attribute has already been taken.',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required' => 'Must Select a Customer',
        ];

        $this->validate($request, [
            'name'                => 'required',
            'transaction_type_id' => 'required',
            'client_id'           => 'required'
        ], $messages);

        $time_now     = date('Y-m-d H:i:s');
        $today     = date('Y-m-d');

        $data = array(
            'name' => $request->name,
            'client_id' => $request->client_id,
            'courier_id' => $request->courier_id,
            'tracking' => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            //'date' => $request->date,
            'date' => $today,
            'reference' => $request->reference,
            'created_at'   => $time_now,
            'updated_at'   => $time_now
        );

        $order = Order::create($data);

        $lastInsertedId= $order->id;

        if($order){
            $order_id = $lastInsertedId;

            $order_lines = json_decode($request->vars);
            foreach ($order_lines as $order_line )
            {
                $purchases_item_id = $order_line->product_id;
                $data_order_item = array(
                    'order_id'     => $order_id,
                    'purchases_id' => $purchases_item_id,
                    'qty'          => $order_line->qty,
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );
                // Insert PO Items Associated to PO
                OrderItems::insert($data_order_item);

                // Saving Stock
                $stock = new StockController();
                $stock->reduceProductStock($purchases_item_id, $order_line->qty);

            }

        }
        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        dd($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Get List of Purchases Items by Json
     * @return JsonResponse
     */
    public function getPurchasesItemsbyAjax() {

        // Get Purchases Items
        $purchases_items = PurchasesItem::join('products', 'purchases_items.product_id', '=', 'products.id')
            ->join('purchases', 'purchases_items.purchases_id', '=', 'purchases.id')
            ->rightjoin('stocks', 'purchases_items.id', '=', 'stocks.purchases_item_id')
            ->select('products.id AS product_id','products.name AS product_name', 'purchases_items.batch_number AS batch', 'purchases.name AS po_name', 'stocks.available AS  available', 'purchases_items.id', 'purchases.id AS po_id')
            ->get();

        // Get all unique items.
        //$purchases_items = $purchases_items->unique('id');

        $data2 = [];
        $i=0;
        foreach ($purchases_items as $purchase_item)
        {
            $data2[$i]['id'] = $purchase_item->id;
            $data2[$i]['text'] = 'Product: '.$purchase_item->product_name.' | PO: '.$purchase_item->po_name.' | Available ('.$purchase_item->available.')';
            $data2[$i]['product_id'] = $purchase_item->po_id;
            $data2[$i]['name'] = $purchase_item->product_name;
            $data2[$i]['batch'] = $purchase_item->batch;
            $data2[$i]['po_name'] = $purchase_item->po_name;
            $data2[$i]['po_id'] = $purchase_item->po_id;
            $data2[$i]['po_item_id'] = $purchase_item->id;
            $data2[$i]['available'] = $purchase_item->available;
            $i++;
        }
        $purchases_items = $data2;
        return response()->json(['products' => $purchases_items]);
    }




}
