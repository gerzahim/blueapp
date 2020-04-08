<?php

namespace App\Http\Controllers;

use App\Client;
use App\Order;
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
use App\Http\Traits\MessagesValidationTrait;
use App\Http\Traits\RulesValidationTrait;
use App\Http\Traits\DataFormTrait;


class PurchaseController extends Controller
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
        return view('purchases.create');
    }


    /**
     * Create New PO and Stock
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        // Validation Form
        $messages = $this->getMessagesValidationPO();
        $rules    = $this->getRulesValidationPO($request, false);
        $this->validate($request, $rules, $messages);

        // Insert Data Purchase
        $data_form = $this->setDataPurchase($request);
        $purchases = Purchases::create($data_form);
        $lastInsertedId= $purchases->id;

        //Insert Purchase Items
        if($purchases){
            $purchases_id = $lastInsertedId;

            $po_lines = json_decode($request->vars);
            foreach ($po_lines as $po_line )
            {
                $data_form_items = $this->setDataPurchaseItems($purchases_id, $po_line);

                // Insert PO Items Associated to PO
                $purchases_item    = PurchasesItem::create($data_form_items);
                $lastInsertedId    = $purchases_item->id;
                $purchases_item_id = $lastInsertedId;

                // Saving Stock
                $stock = new StockController();
                $stock->registerProductStock($purchases_id, $purchases_item_id, $po_line->product_id, $po_line->qty);

            }

        }
        return redirect()->route('purchases.index')->with('success', 'PO created successfully.');
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
        $form_action = 'edit';

        $purchase = Purchases::where('id',$id)->first();

        $po_lines  = PurchasesItem::where('purchases_id',$id)->get();
        $products_pos = [];

        foreach($po_lines as $po_line){
            $po_line->name  = Product::where('id', $po_line->product_id)->first()->name;
            $products_pos[] = array('product_id' => $po_line->product_id, 'product_name' => $po_line->name, 'qty' => $po_line->qty, 'batch_number' => $po_line->batch_number);
        }
        $products_po = json_encode($products_pos, true);

        return view('purchases.edit', compact('form_action', 'purchase', 'products_po'));

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
        $messages = $this->getMessagesValidationPO();
        $rules    = $this->getRulesValidationPO($request, true);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect('editPurchase/'.$request->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data_form = $this->setDataPurchase($request);

        $purchases = Purchases::find($request->id);
        $purchases->fill($data_form)->save();

        $po_lines = json_decode($request->vars);

        PurchasesItem::where('purchases_id',$request->id)->delete();
        Stock::where('purchases_id',$request->id)->delete();

        foreach ($po_lines as $po_line )
        {
            $data_form_items = $this->setDataPurchaseItems($request->id, $po_line);

            // Insert PO Items Associated to PO
            $purchases_item    = PurchasesItem::create($data_form_items);
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
}
