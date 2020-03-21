<?php

namespace App\Http\Controllers;

use App\Client;
use App\Order;
use App\Purchases;
use App\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::latest()->paginate(10);
        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:vendors|max:100'
        ]);
        Vendor::create($request->only(['name','email','contact_person','notes','address1','address2','city','state','postal_code','country','phone','mobile']));

        return redirect()->route('vendor.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor') );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Vendor $vendor
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:vendors,name,'.$vendor->id
        ]);
        $vendor->update($request->only(['name','email','contact_person','notes','address1','address2','city','state','postal_code','country','phone','mobile']));
        return redirect()->route('vendor.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Vendor $vendor
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Vendor $vendor)
    {
        //Verify if have Order Associated
        $order = Purchases::where('contact_id', $vendor->id)->where('contact_type_id', 1)->first();
        if($order){
            return redirect()->route('vendor.index')->with('warning', 'Can Not Delete this Supplier, have at least 1 PO Associated');
        }

        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Supplier has been deleted successfully!');
    }
}
