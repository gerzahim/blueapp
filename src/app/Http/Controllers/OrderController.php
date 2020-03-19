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

        return view('orders.create', compact('lastOrder') );
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
            'name.required'    => 'The Order Number attribute has already been taken.',
            'date.required'    => 'Must Select a Date',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required' => 'Must Select a Customer',
        ];

        $this->validate($request, [
            'name'                => 'required',
            'date'                => 'required',
            'transaction_type_id' => 'required',
            'client_id'           => 'required'
        ], $messages);

        $time_now     = date('Y-m-d H:i:s');


        $data = array(
            'name' => $request->name,
            'client_id' => $request->client_id,
            'courier_id' => $request->courier_id,
            'tracking' => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'date' => $request->date,
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

                // Reduce PurchaseItem ******

                // Reduce Stock
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
     * @param \App\Order $order
     * @return void
     */
    public function edit(Order $order)
    {
        $order_lines  = OrderItems::where('order_id',$order->id)->get();
        $products_order = [];
        foreach($order_lines as $order_line){

            $po_line  = PurchasesItem::where('id',$order_line->purchases_id)->first();
            $po       = Purchases::where('id',$po_line->purchases_id)->first();
            $product  = Product::where('id', $po_line->product_id)->first();
            $stock    = Stock::where('purchases_item_id',$order_line->purchases_id)->first();


            $products_order[] = array(
                'po_id'        => $order_line->purchases_id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'batch'        => $po_line->batch_number,
                'po_name'      => $po->name,
                'available'    => $stock->available,
                'qty'          => $order_line->qty
            );
        }

        $products_order = json_encode($products_order, true);

        return view('orders.edit', compact('order', 'products_order'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Order $order)
    {
        $rules = [
            'name'                => 'required|max:50|unique:orders,name,'.$request->id,
            'date'                => 'required',
            'transaction_type_id' => 'required',
            'client_id'           => 'required'
        ];
        $messages = [
            'name.required'    => 'The Order Number attribute has already been taken.',
            'date.required'    => 'Must Select a Date',
            'transaction_type_id.required'    => 'The Transaction Type must be Selected.',
            'client_id.required' => 'Must Select a Customer'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('order/'.$request->id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $time_now = date('Y-m-d H:i:s');

        $data = array(
            'name' => $request->name,
            'client_id' => $request->client_id,
            'courier_id' => $request->courier_id,
            'tracking' => $request->tracking,
            'transaction_type_id' => $request->transaction_type_id,
            'date' => $request->date,
            'reference' => $request->reference,
            'created_at'   => $time_now,
            'updated_at'   => $time_now
        );
        //$order->fill($data)->save();

        //$po_lines = json_decode($request->vars);
        //OrderItems::where('order_id',$order->id)->delete();
        //Saving Stock
        //$stock = new StockController();
        //$stock->reduceProductStock($purchases_item_id, $order_line->qty);
        //Stock::where('purchases_id',$order->id)->delete();
        /*
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
            $purchases_item = PurchasesItem::create($data_po);
            $lastInsertedId = $purchases_item->id;
            $purchases_item_id = $lastInsertedId;

            // Saving Stock
            $stock = new StockController();
            $stock->registerProductStock($request->id, $purchases_item_id, $po_line->product_id, $po_line->qty);
        }*/

        $order_lines = json_decode($request->vars);

        foreach ($order_lines as $order_line )
        {
            $purchases_item_id = $order_line->product_id;
            $data_order_item = array(
                'order_id'     => $order->id,
                'purchases_id' => $order_line->po_id,
                'qty'          => $order_line->qty,
                'created_at'   => $time_now,
                'updated_at'   => $time_now
            );

            $or_line  = OrderItems::where('purchases_id',$order_line->po_id)->first();

            if ($or_line){
                // Update OrderItem
                $or_line->fill($data_order_item)->save();

                // Update PurchaseItem *********
                // Update Stock  **********
            }else{

                // Insert PO Items Associated to PO
                OrderItems::insert($data_order_item);

                // Reduce PurchaseItem ******

                // Saving Stock
                $stock = new StockController();
                $stock->reduceProductStock($purchases_item_id, $order_line->qty);
            }
        }
        return redirect()->route('order.index')->with('success', 'Post has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        //Get All Order Lines associated to the Order
        $OrderItems = OrderItems::where('order_id',$order->id)->get();
        foreach ($OrderItems as $OrderItem){
            // Updating Stock
            $stock = new StockController();
            $stock->reverseReduceProductStock($OrderItem->purchases_id, $OrderItem->qty);
        }
        // Delete Order Lines
        $OrderItems = OrderItems::where('order_id',$order->id)->delete();

        //Delete Order
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order has been deleted successfully!');
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
            $data2[$i]['product_id'] = $purchase_item->product_id;
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
