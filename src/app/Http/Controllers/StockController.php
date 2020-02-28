<?php

namespace App\Http\Controllers;

use App\Purchases;
use App\Stock;
use App\Product;
use Illuminate\Http\Request;


class StockController extends Controller
{
    public $purchases_id;
    public $product_id;
    public $purchased;
    public $sold; //out
    public $qoh; //out-in
    public $on_hold; //out
    public $available; //out
    public $rma; //out
    public $refurbished; //in
    public $lended; //out

    public $products = [];
    public $purchases = [];


    /**
     * Display a listing of Stock
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $prods = Product::all();
        $prods->each(function ($item, $key) {
            $this->products[$item->id]=$item->name;
        });


        $pos = Purchases::all();
        $pos->each(function ($item, $key) {
            $this->purchases[$item->id]=$item->name;
        });

        $products  = $this->products;
        $purchases = $this->purchases;
        $stocks    = Stock::latest()->paginate(10);

        return view('stock.index', compact('stocks','products', 'purchases'));
    }

    /**
     * Saving Stock
     */
    public function saveStock() {

        $data = array(
            'purchases_id'  => $this->purchases_id,
            'product_id'    => $this->product_id,
            'purchased'      => $this->purchased,
            'sold'         => $this->sold,
            'qoh'           => $this->qoh,
            'on_hold'       => $this->on_hold,
            'available'     => $this->available,
            'rma'           => $this->rma,
            'refurbished'   => $this->refurbished,
            'lended'        => $this->lended
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
    public function registerProductStock($po, $product, $qty) {
        $this->purchases_id = $po;
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