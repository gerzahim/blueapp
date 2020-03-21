<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
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
            'name' => 'required|unique:clients|max:100'
        ]);
        Client::create($request->only(['name','email','contact_person','notes','address1','address2','city','state','postal_code','country','phone','mobile','ein_number','resale_tax']));

        return redirect()->route('client.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client') );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, [
            'name' => 'required|max:100|unique:clients,name,'.$client->id
        ]);
        $client->update($request->only(['name','email','contact_person','notes','address1','address2','city','state','postal_code','country','phone','mobile','ein_number','resale_tax']));
        return redirect()->route('client.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        $client->delete();
        //pending// No delete Client if has Orders Opened
        return redirect()->route('client.index')->with('success', 'Customer has been deleted successfully!');
    }
}
