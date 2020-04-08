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
use App\Http\Traits\MessagesValidationTrait;
use App\Http\Traits\RulesValidationTrait;
use App\Http\Traits\DataFormTrait;


class OrderController extends Controller
{
    use MessagesValidationTrait;
    use RulesValidationTrait;
    use DataFormTrait;

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
        // Validation Form
        $messages = $this->getMessagesValidationOrders();
        $rules    = $this->getRulesValidationOrders($request, false);
        $this->validate($request, $rules, $messages);

        // Insert Data Purchase
        $data_form = $this->setDataOrder($request);
        $order = Order::create($data_form);
        $lastInsertedId= $order->id;

        if($order){
            $order_id = $lastInsertedId;

            $order_lines = json_decode($request->vars);
            foreach ($order_lines as $order_line )
            {
                $data_form_items = $this->setDataOrderItems($order_id, $order_line);

                // Insert PO Items Associated to PO
                OrderItems::insert($data_form_items);

                // Reduce Stock
                $stock = new StockController();
                $stock->reduceProductStock($order_line->po_item_id, $order_line->qty);
            }

        }
        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     * @param Order $order
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
                'po_id'        => $po->id,
                'po_item_id'   => $po_line->id,
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
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Order $order)
    {
        // Validation Form
        $messages = $this->getMessagesValidationOrders();
        $rules    = $this->getRulesValidationOrders($request, true);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('order/'.$order->id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $data_form = $this->setDataOrder($request, true);

        $order->fill($data_form)->save();

        $order_lines = json_decode($request->vars);
        $stock = new StockController();
        $orders_items_updated = [];

        // { Update if Exist -> Create if New -> Delete Not Found
        foreach ( $order_lines as $order_line )
        {
            $data_form_items = $this->setDataOrderItems($order->id, $order_line);

            // If Exist
            $or_line  = OrderItems::where('order_id',$order->id)->where('purchases_id',$order_line->po_item_id)->first();
            if ($or_line){
                // Update Stock
                $stock->adjustProductStock($order_line->po_item_id, $or_line->qty, $order_line->qty);

                // Update OrderItem Values
                $or_line->fill($data_form_items)->save();
                $orders_items_updated[] = $or_line->id;
            }else{
                // NEW

                // Insert Order Items Associated to Order
                $order_item = OrderItems::create($data_form_items);
                $lastInsertedId = $order_item->id;
                $orders_items_updated[] = $lastInsertedId;

                // Saving Stock
                $stock->reduceProductStock($order_line->po_item_id, $order_line->qty);
            }
        }

        // Update Stock for Items_no_longer_used
        $order_items_no_longer_used = OrderItems::where('order_id', $order->id)->whereNotIn('id', $orders_items_updated)->get();
        foreach ($order_items_no_longer_used as $order_item_no_longer_used){
            $stock->reverseReduceProductStock($order_item_no_longer_used->purchases_id, $order_item_no_longer_used->qty);
        }

        // Delete OrderItems No_longer_used
        OrderItems::where('order_id', $order->id)->whereNotIn('id', $orders_items_updated)->delete();


        return redirect()->route('order.index')->with('success', 'Order has been updated successfully!');
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
        OrderItems::where('order_id',$order->id)->delete();

        //Delete Order
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order has been deleted successfully!');
    }
}
