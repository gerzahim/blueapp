<?php

namespace App\Http\Controllers;

use App\PurchasesItem;
use App\Refurbishes;
use App\RefurbishItems;
use App\RMA;
use App\RMAItems;
use Illuminate\Http\Request;

class RefurbishesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refurbishes = Refurbishes::latest()->paginate(10);
        return view('refurbishes.index', compact('refurbishes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lastOrder = Refurbishes::select('id')->orderBy('id','desc')->first();
        if($lastOrder){
            $lastOrder = $lastOrder->id+1;
        }else{
            $lastOrder = 1;
        }

        return view('refurbishes.create', compact('lastOrder') );
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
            'name.required' => 'The Refurbished Number is Required.',
            'date.required' => 'Must Select a Date',
        ];
        $this->validate($request, [
            'name' => 'required',
            'date' => 'required',
        ], $messages);

        $time_now = date('Y-m-d H:i:s');

        $data = array(
            'name'                => $request->name,
            'transaction_type_id' => 6,
            'date'                => $request->date,
            'reference'           => $request->reference,
            'created_at'          => $time_now,
            'updated_at'          => $time_now
        );
        $refurbish = Refurbishes::create($data);
        $lastInsertedId= $refurbish->id;

        if($refurbish){
            $refurbishes_lines = json_decode($request->vars);
            foreach ($refurbishes_lines as $refurbish_line )
            {
                $data_order_item = array(
                    'refurbish_id' => $lastInsertedId,
                    'product_id'   => $refurbish_line->product_id,
                    'qty'          => $refurbish_line->qty,
                    'purchases_id' => $refurbish_line->po_item_id,
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );
                // Insert Refurbish Items
                RefurbishItems::insert($data_order_item);

                // Reduce FROM RMA
                $stock = new StockController();
                $stock->reduceProductFromRMAStock($refurbish_line->po_item_id, $refurbish_line->qty);
            }
        }
        return redirect()->route('refurbishes.index')->with('success', 'Refurbish created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Refurbishes  $refurbishes
     * @return \Illuminate\Http\Response
     */
    public function show(Refurbishes $refurbishes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Refurbishes  $refurbishes
     * @return \Illuminate\Http\Response
     */
    public function edit(Refurbishes $refurbishes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Refurbishes  $refurbishes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Refurbishes $refurbishes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Refurbishes  $refurbishes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refurbishes $refurbishes)
    {
        //
    }
}
