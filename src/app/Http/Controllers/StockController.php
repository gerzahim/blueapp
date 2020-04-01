<?php

namespace App\Http\Controllers;

use App\Purchases;
use App\PurchasesItem;
use App\Stock;
use App\Product;
use Debugbar;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public $purchases_id;
    public $purchases_item_id;
    public $product_id;
    public $purchased;
    public $sold; //out
    public $qoh; //out-in
    public $on_hold; //out
    public $available; //out
    public $rma; //out
    public $refurbished; //in
    public $lended; //out

    public $products_name  = [];
    public $products_batch = [];
    public $purchases_name = [];


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
     * Display a listing of Stock
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $prods = Product::all();
        $prods->each(function ($item, $key) {
            $this->products_name[$item->id]=$item->name;
            $this->products_batch[$item->id]=$item->batch_number;
        });

        $pos = Purchases::all();
        $pos->each(function ($item, $key) {
            $this->purchases_name[$item->id]=$item->name;
        });

        $products_batch = PurchasesItem::all();
        $products_batch->each(function ($item, $key) {
            //Debugbar::info($item);
            $this->products_batch[$item->id]=$item->batch_number;
        });

        $products_name  = $this->products_name;
        $products_batch = $this->products_batch;
        $purchases_name = $this->purchases_name;
        $stocks         = Stock::latest()->paginate(10);

        return view('stock.index', compact('stocks','products_name', 'products_batch', 'purchases_name'));
    }

    /**
     * Saving Stock
     */
    public function saveStock() {

        $data = array(
            'purchases_id'       => $this->purchases_id,
            'purchases_item_id'  => $this->purchases_item_id,
            'product_id'         => $this->product_id,
            'purchased'          => $this->purchased,
            'sold'               => $this->sold,
            'qoh'                => $this->qoh,
            'on_hold'            => $this->on_hold,
            'available'          => $this->available,
            'rma'                => $this->rma,
            'refurbished'        => $this->refurbished,
            'lended'             => $this->lended
        );
        Stock::insert($data);

    }

    /**
     * Make calculation when create a PO
     *
     * @param $po
     * @param $product
     * @param $qty
     */
    public function registerProductStock($po, $purchases_item_id, $product, $qty) {
        $this->purchases_id = $po;
        $this->purchases_item_id = $purchases_item_id;
        $this->product_id   = $product;
        $this->purchased    = $qty;
        $this->sold         = 0;
        $this->qoh          = $qty;
        $this->on_hold      = 0;
        $this->available    = $qty;
        $this->rma          = 0;
        $this->refurbished  = 0;
        $this->lended       = 0;

        $this->saveStock();
    }



    /**
     * * Make calculation when create a New Order
     *
     * @param $purchases_item_id
     * @param $qty
     */
    public function reduceProductStock($purchases_item_id, $qty) {
        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        $stock->sold = ($stock->sold + $qty);
        $stock->qoh = ($stock->qoh - $qty);
        $stock->available = ($stock->available - $qty);
        $stock->save();
    }

    /**
     * * Add Products defective to RMA and reduce from Inventory
     *
     * @param $purchases_item_id
     * @param $qty
     */
    public function addProductRMA_Vendor($purchases_item_id, $qty) {
        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        $stock->qoh = ($stock->qoh - $qty);
        $stock->available = ($stock->available - $qty);
        $stock->rma = ($stock->rma + $qty);
        $stock->save();
    }

    /**
     * * Add Products defective to RMA from Customer Return
     *
     * @param $purchases_item_id
     * @param $qty
     */
    public function addProductRMA($purchases_item_id, $qty) {
        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        $stock->rma = ($stock->rma + $qty);
        $stock->save();
    }

    /**
     * * Add Products defective to RMA from Customer Return
     *
     * @param $purchases_item_id
     * @param $qty_previous
     * @param $qty_new
     * @param $is_vendor
     */
    public function adjustProductRMA($purchases_item_id,  $qty_previous, $qty_new, $is_vendor) {

        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        if($is_vendor){
            $stock->qoh = ($stock->qoh - $qty_previous) + $qty_new;
            $stock->available = ($stock->available - $qty_previous) + $qty_new;
        }
        $stock->rma = ($stock->rma - $qty_previous) + $qty_new;
        $stock->save();
    }

    /**
     * * Make calculation when create a New Order
     *
     * @param $purchases_item_id
     * @param $qty
     * @param $is_vendor
     */
    public function reverseProductRMA($purchases_item_id, $qty, $is_vendor) {

        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        if($is_vendor){
            $stock->qoh = ($stock->qoh + $qty);
            $stock->available = ($stock->available + $qty);
        }
        $stock->rma = ($stock->rma - $qty);
        $stock->save();
    }

    /**
     * * Make calculation when create a New Order
     *
     * @param $purchases_item_id
     * @param $qty_previous
     * @param $qty_new
     */
    public function adjustProductStock($purchases_item_id, $qty_previous, $qty_new) {
        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        $stock->sold = ($stock->sold - $qty_previous) + $qty_new;
        $stock->qoh = ($stock->qoh + $qty_previous) - $qty_new;
        $stock->available = ($stock->available + $qty_previous) - $qty_new;
        $stock->save();

    }


    /**
     * * Make calculation when create a New Order
     *
     * @param $purchases_item_id
     * @param $qty
     */
    public function reverseReduceProductStock($purchases_item_id, $qty) {
        $stock = Stock::where('purchases_item_id',$purchases_item_id)->first();
        $stock->sold = ($stock->sold - $qty);
        $stock->qoh = ($stock->qoh + $qty);
        $stock->available = ($stock->available + $qty);
        $stock->save();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
