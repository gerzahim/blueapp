<?php

namespace App\Http\Controllers;

use App\Client;
use App\ContactType;
use App\Order;
use App\RMA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RMA  $rma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RMA $rma)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RMA  $rma
     * @return \Illuminate\Http\Response
     */
    public function destroy(RMA $rma)
    {
        //
    }
}
