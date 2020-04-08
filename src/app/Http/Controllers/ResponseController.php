<?php

namespace App\Http\Controllers;

use App\Client;
use App\Courier;
use App\Order;
use App\Product;
use App\Purchases;
use App\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ResponseController extends Controller
{
    /**
     * Get List of Products by Json
     * @return JsonResponse
     */
    public function getProductsbyAjax() {
        $products = Product::all();
        $sorted   = $products->sortBy('name');
        $products = $sorted->values()->all();
        return response()->json(['products' => $products]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getClientsDT() {
        $clients  = Client::all();
        $subset = $clients->map->only(['id','name']);
        return response()->json(['data' => $subset]);
    }


    /**
     * Get List of Client with at least one Order
     *
     * @return JsonResponse
     */
    public function getClientsWithOrders() {
        $orders        = Order::all();
        $uniqueClients = $orders->unique('client_id');

        $ClientsID = [];
        foreach ($uniqueClients as $uniqueClient){
            $ClientsID[] = $uniqueClient->client_id;
        }
        $clients  = Client::whereIn('id',$ClientsID)->get();
        $sorted   = $clients->sortBy('name');
        $clients  = $sorted->values()->all();
        $clients  = json_encode($clients);
        return $clients;

    }

    /**
     * Get List of Client with at least one Order
     *
     * @return JsonResponse
     */
    public function getVendorsWithOrders() {
        $purchases     = Purchases::all();
        $uniqueVendors = $purchases->unique('contact_id');

        $vendorsID = [];
        foreach ($uniqueVendors as $uniqueVendor){
            $vendorsID[] = $uniqueVendor->contact_id;
        }
        $vendors  = Vendor::whereIn('id',$vendorsID)->get();
        $sorted   = $vendors->sortBy('name');
        $vendors  = $sorted->values()->all();
        $vendors  = json_encode($vendors);
        return $vendors;
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getClientsbyAjax() {
        $clients  = Client::all();
        $sorted   = $clients->sortBy('name');
        $clients  = $sorted->values()->all();
        return response()->json(['clients' => $clients]);
    }

    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getVendorsbyAjax() {
        $vendors = Vendor::all();
        $sorted  = $vendors->sortBy('name');
        $vendors = $sorted->values()->all();
        return response()->json(['vendors' => $vendors]);
    }


    /**
     * Get List of Products by Json
     *
     * @return JsonResponse
     */
    public function getCouriersbyAjax() {

        $courier_na = Courier::where('name', 'N/A')->get();

        $couriers = Courier::whereNotIn('name', ['N/A'])->get();
        $sorted   = $couriers->sortBy('name');
        $couriers = $sorted->values()->all();

        // Merge Both Collection $merged = $original->merge($latest);
        $merged = $courier_na->merge($couriers);

        return response()->json(['couriers' => $merged]);
    }
}
