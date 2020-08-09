<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Courier;
use App\Order;
use App\OrderItems;
use App\Product;
use App\ProductDimensions;
use App\Purchases;
use App\PurchasesItem;
use App\RMAItems;
use App\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Traits\PaddingStringsTrait;


class ResponseController extends Controller
{
    use PaddingStringsTrait;


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
     * Get List of Clients by Json
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
        return response()->json(['clients' => $clients]);

    }

    /**
     * Get List of Vendor with at least one PO
     *
     * @return JsonResponse
     */
    public function getVendorsWithPO() {
        $purchases     = Purchases::all();
        $uniqueVendors = $purchases->unique('contact_id');

        $vendorsID = [];
        foreach ($uniqueVendors as $uniqueVendor){
            $vendorsID[] = $uniqueVendor->contact_id;
        }
        $vendors  = Vendor::whereIn('id',$vendorsID)->get();
        $sorted   = $vendors->sortBy('name');
        $vendors  = $sorted->values()->all();
        /*
         * Used this way if need send directly as props
        $vendors  = json_encode($vendors);
        return $vendors;
        */
        return response()->json(['vendors' => $vendors]);
    }

    /**
     * Get List of Clients by Json
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
    /**
     * Get List of Purchases Items by Json
     * @return JsonResponse
     */
    public function getPurchasesItemsbyAjax() {

        // Get Purchases Items
        $purchases_items = PurchasesItem::join('products', 'purchases_items.product_id', '=', 'products.id')
            ->join('purchases', 'purchases_items.purchases_id', '=', 'purchases.id')
            ->rightjoin('stocks', 'purchases_items.id', '=', 'stocks.purchases_item_id')
            ->select('products.id AS product_id','products.name AS product_name', 'purchases_items.batch_number AS batch', 'purchases.name AS po_name', 'stocks.available AS  available', 'purchases_items.id', 'purchases.id AS po_id')
            ->get();

        $data2 = [];
        $i=0;
        foreach ($purchases_items as $purchase_item)
        {
            $data2[$i]['id'] = $purchase_item->id;
            //$data2[$i]['text'] = $purchase_item->product_name.' | PO: '.$purchase_item->po_name.' | Av ('.$purchase_item->available.')';
            $data2[$i]['text'] = $this->formatPadString($purchase_item->product_name,$purchase_item->po_name,$purchase_item->available);
            $data2[$i]['product_id'] = $purchase_item->product_id;
            $data2[$i]['name'] = $purchase_item->product_name;
            $data2[$i]['batch'] = $purchase_item->batch;
            $data2[$i]['po_name'] = $purchase_item->po_name;
            $data2[$i]['po_id'] = $purchase_item->po_id;
            $data2[$i]['po_item_id'] = $purchase_item->id;
            $data2[$i]['available'] = $purchase_item->available;
            $i++;
        }
        $purchases_items = $data2;
        return response()->json(['products' => $purchases_items]);
    }

    /**
     * Get List of Orders by Customer
     * @return JsonResponse
     */
    public function getOrderByCustomerID($id) {


        $orders = Order::where('client_id',$id)->get();
        $ordersID = [];
        foreach ($orders as $order){
            $ordersID[] = $order->id;
        }
        $orders_items  = OrderItems::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('purchases_items', 'order_items.purchases_id', '=', 'purchases_items.id')
            ->join('purchases', 'purchases_items.purchases_id', '=', 'purchases.id')
            ->join('products', 'purchases_items.product_id', '=', 'products.id')
            ->select('products.id AS product_id','products.name AS product_name', 'purchases_items.batch_number AS batch',
                'purchases.name AS po_name', 'purchases_items.id', 'purchases.id AS po_id', 'orders.name AS order_name', 'orders.id AS order_id', 'order_items.qty AS available')
            ->whereIn('order_items.order_id',$ordersID)->get();

        $data2 = [];
        $i=0;
        foreach ($orders_items as $order_item)
        {
            $data2[$i]['id'] = $order_item->id;
            $data2[$i]['text'] = $this->formatPadString2($order_item->product_name,$order_item->po_name, $order_item->order_name, $order_item->available);
            $data2[$i]['product_id'] = $order_item->product_id;
            $data2[$i]['name'] = $order_item->product_name;
            $data2[$i]['batch'] = $order_item->batch;
            $data2[$i]['order_id'] = $order_item->order_id;
            $data2[$i]['po_name'] = $order_item->po_name;
            $data2[$i]['po_id'] = $order_item->po_id;
            $data2[$i]['po_item_id'] = $order_item->id;
            $data2[$i]['available'] = $order_item->available;
            $i++;
        }
        $orders_items = $data2;
        return response()->json(['products' => $orders_items]);
    }

    /**
     * Get List of Orders by Customer
     * @return JsonResponse
     */
    public function getPurchasesByVendorID($id) {

        $purchases = Purchases::where('contact_type_id',1)->where('contact_id',$id)->get();
        $posID = [];
        foreach ($purchases as $purchase){
            $posID[] = $purchase->id;
        }

        // Get Purchases Items
        $purchases_items = PurchasesItem::join('products', 'purchases_items.product_id', '=', 'products.id')
            ->join('purchases', 'purchases_items.purchases_id', '=', 'purchases.id')
            ->rightjoin('stocks', 'purchases_items.id', '=', 'stocks.purchases_item_id')
            ->select('products.id AS product_id','products.name AS product_name', 'purchases_items.batch_number AS batch',
                'purchases.name AS po_name', 'stocks.available AS  available', 'purchases_items.id', 'purchases.id AS po_id')
            ->whereIn('purchases_items.purchases_id',$posID)->get();

        $data3 = [];
        $i=0;
        foreach ($purchases_items as $purchase_item)
        {
            $data3[$i]['id'] = $purchase_item->id;
            $data3[$i]['text'] = $this->formatPadString($purchase_item->product_name,$purchase_item->po_name,$purchase_item->available);
            $data3[$i]['product_id'] = $purchase_item->product_id;
            $data3[$i]['name'] = $purchase_item->product_name;
            $data3[$i]['batch'] = $purchase_item->batch;
            $data3[$i]['order_id'] = 0;
            $data3[$i]['po_name'] = $purchase_item->po_name;
            $data3[$i]['po_id'] = $purchase_item->po_id;
            $data3[$i]['po_item_id'] = $purchase_item->id;
            $data3[$i]['available'] = $purchase_item->available;
            $i++;
        }
        $purchases_items = $data3;
        return response()->json(['products' => $purchases_items]);
    }

    /**
     * Get List of Purchases Items by Json
     * @return JsonResponse
     */
    public function getRMAItemsbyAjax() {

        // Get Purchases Items
        $rma_items = RMAItems::join('products', 'rma_items.product_id', '=', 'products.id')
            ->join('purchases_items', 'rma_items.purchases_id', '=', 'purchases_items.id')
            ->join('purchases', 'purchases_items.purchases_id', '=', 'purchases.id')
            ->join('stocks', 'purchases_items.id', '=', 'stocks.purchases_item_id')
            ->select('products.id AS product_id','products.name AS product_name', 'purchases_items.batch_number AS batch', 'purchases.name AS po_name', 'rma_items.qty AS  available', 'purchases_items.id', 'purchases.id AS po_id')
            ->get();

        $data2 = [];
        $i=0;
        foreach ($rma_items as $purchase_item)
        {
            $data2[$i]['id'] = $purchase_item->id;
            $data2[$i]['text'] = $this->formatPadString3($purchase_item->product_name,$purchase_item->po_name,$purchase_item->available);
            $data2[$i]['product_id'] = $purchase_item->product_id;
            $data2[$i]['name'] = $purchase_item->product_name;
            $data2[$i]['batch'] = $purchase_item->batch;
            $data2[$i]['po_name'] = $purchase_item->po_name;
            $data2[$i]['po_id'] = $purchase_item->po_id;
            $data2[$i]['po_item_id'] = $purchase_item->id;
            $data2[$i]['available'] = $purchase_item->available;
            $i++;
        }
        $purchases_items = $data2;
        return response()->json(['products' => $purchases_items]);
    }



    /**
     * Get List of Categories by Json
     *
     * @return JsonResponse
     */
    public function getCategoriesAjax() {
        $categories = Category::all();
        $sorted  = $categories->sortBy('name');
        $categories = $sorted->values()->all();
        return response()->json(['categories' => $categories]);
    }

    /**
     * Get List of Products Dimensions by Json
     *
     * @return JsonResponse
     */
    public function getProductsDimensionsAjax() {
        $dimensions = ProductDimensions::all();
        $sorted  = $dimensions->sortBy('name');
        $dimensions = $sorted->values()->all();
        return response()->json(['dimensions' => $dimensions]);
    }



}
