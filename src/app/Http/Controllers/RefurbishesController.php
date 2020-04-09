<?php

namespace App\Http\Controllers;


use App\PurchasesItem;
use App\Refurbishes;
use App\RefurbishItems;
use App\Purchases;
use App\Product;
use App\RMA;
use App\RMAItems;
use Illuminate\Http\Request;
use App\Http\Traits\DataFormTrait;
use App\Http\Traits\MessagesValidationTrait;
use App\Http\Traits\RulesValidationTrait;

class RefurbishesController extends Controller
{
    use MessagesValidationTrait;
    use RulesValidationTrait;
    use DataFormTrait;

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
     * @param Refurbishes $refurbish
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Refurbishes $refurbish)
    {
        $refurbishes_lines  = RefurbishItems::where('refurbish_id',$refurbish->id)->get();

        $products_refurbishes = [];
        foreach($refurbishes_lines as $refurbish_line){
            $po_line  = PurchasesItem::where('id',$refurbish_line->purchases_id)->first();
            $po       = Purchases::where('id',$po_line->purchases_id)->first();
            $product  = Product::where('id', $po_line->product_id)->first();
            $rma_item = RMAItems::where('purchases_id',$refurbish_line->purchases_id)->first();

            $products_refurbishes[] = array(
                'po_id'        => $po->id,
                'po_item_id'   => $po_line->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'batch'        => $po_line->batch_number,
                'po_name'      => $po->name,
                'available'    => $rma_item->qty,
                'qty'          => $refurbish_line->qty
            );
        }
        $products_refurbishes = json_encode($products_refurbishes, true);

        return view('refurbishes.edit', compact('refurbish', 'products_refurbishes') );
    }

    /**
     * @param Request $request
     * @param Refurbishes $refurbish
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Refurbishes $refurbish)
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
        $refurbish->fill($data)->save();

        $stock = new StockController();
        $refurbish_items_updated = [];

        $refurbishes_lines = json_decode($request->vars);

        if($refurbish){

            foreach ($refurbishes_lines as $refurbish_line )
            {
                $data_order_item = array(
                    'refurbish_id' => $refurbish->id,
                    'product_id'   => $refurbish_line->product_id,
                    'qty'          => $refurbish_line->qty,
                    'purchases_id' => $refurbish_line->po_item_id,
                    'created_at'   => $time_now,
                    'updated_at'   => $time_now
                );


                // If Exist
                $refurbish_line_previous  = RefurbishItems::where('refurbish_id',$refurbish->id)->where('purchases_id',$refurbish_line->po_item_id)->first();

                if ($refurbish_line_previous){
                    // IF Exist
                    $stock->adjustProductFromRMAStock($refurbish_line_previous->purchases_id, $refurbish_line_previous->qty, $refurbish_line->qty);

                    // Update RefurbishItems Values
                    $refurbish_line_previous->fill($data_order_item)->save();
                    $refurbish_items_updated[] = $refurbish_line_previous->id;
                }else{
                    // Insert RMA Items if New
                    $new_rma_item = RefurbishItems::create($data_order_item);
                    $lastInsertedId = $new_rma_item->id;
                    $refurbish_items_updated[] = $lastInsertedId;

                    $stock->reduceProductFromRMAStock($refurbish_line->po_item_id, $refurbish_line->qty);
                }

                // Update RMA for Items_no_longer_used
                $rma_items_no_longer_used = RefurbishItems::where('refurbish_id', $refurbish->id)->whereNotIn('id', $refurbish_items_updated)->get();
                foreach ($rma_items_no_longer_used as $rma_item_no_longer_used){
                    $stock->reduceProductFromRMAStock2($rma_item_no_longer_used->purchases_id, $rma_item_no_longer_used->qty);
                }

                // Delete OrderItems No_longer_used
                RefurbishItems::where('refurbish_id', $refurbish->id)->whereNotIn('id', $refurbish_items_updated)->delete();

            }
        }
        return redirect()->route('refurbishes.index')->with('success', 'Refurbish updated successfully.');
    }

    /**
     * @param Refurbishes $refurbish
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Refurbishes $refurbish)
    {
        //Get All Order Lines associated to the Order
        $refurbishes_lines = RefurbishItems::where('refurbish_id',$refurbish->id)->get();
        foreach ($refurbishes_lines as $refurbish_line){
            // Updating Stock
            $stock = new StockController();
            $stock->reduceProductFromRMAStock2($refurbish_line->purchases_id, $refurbish_line->qty);
        }
        // Delete Order Lines
        RefurbishItems::where('refurbish_id',$refurbish->id)->delete();

        //Delete Order
        $refurbish->delete();

        return redirect()->route('refurbishes.index')->with('success', 'Refurbish has been deleted successfully!');
    }
}
