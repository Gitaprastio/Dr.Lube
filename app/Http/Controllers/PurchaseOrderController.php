<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\PurchaseOrder;
use App\Model\PurchaseItem;
use App\Model\User;
use App\Model\DetailUser;
use App\Model\ProductAgreement;
use App\Model\ListItem;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchase()
    {
      $title = "Purchase Order";

      $uid = Auth::id();

      $user = DetailUser::where('user_id', $uid)->first();

      $product["cost"] = ListItem::
      whereHas('agreement', function($q) use ($uid){
          $q->where('user_id', '=', $uid);
      })
      ->get()->pluck('cost');

      $products = ListItem::with('product')
      ->whereHas('agreement', function($q) use ($uid){
          $q->where('user_id', '=', $uid);
      })
      ->get()->pluck('product');

      $product["name"] = ListItem::with('product')
      ->whereHas('agreement', function($q) use ($uid){
          $q->where('user_id', '=', $uid);
      })
      ->get()->pluck('product.product_name');

      $product["id"] = ListItem::with('product')
      ->whereHas('agreement', function($q) use ($uid){
          $q->where('user_id', '=', $uid);
      })
      ->get()->pluck('product.id');

      $product["list_id"] = ListItem::
      whereHas('agreement', function($q) use ($uid){
          $q->where('user_id', '=', $uid);
      })
      ->get()->pluck('id');

      // $product = json_encode($product);
      // dd($product); 

      return view('dashboard.order.index', compact('title','user','products', 'product', 'product_name', 'product_id', 'cost'));
    }

    public function index() 
    {
      $title = "Purchase Order List";
      $uid = Auth::id();

      $purchases = PurchaseOrder::
      where('user_id', $uid)
      ->orderBy('created_at', 'asc')
      ->get();

      // dd($purchases);
      return view('dashboard.order.dashboard', compact('title','purchases'));
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
      // dd($request);
      $uid = Auth::id();
      
      $user = DetailUser::where('user_id', $uid)->first();

      $po = new PurchaseOrder;
      $po->status = 1;
      $po->amount = $request->total_price;
      if ($request->shipping_address) {
        $po->shipping_address = $request->shipping_address;
      }else {
        $po->shipping_address = $user->address;
      }
      $po->user_id = $uid;
      $po->save();

      $pid = $po->id;
      foreach ($request->list_id as $key => $value) {
        $data = new PurchaseItem;
        $data->purchase_order_id = $pid;
        $data->list_item_id = $value;
        $data->quantity = $request->quantity[$key];
        $data->save();
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $title = "Purchase Order Detail";
      $data = PurchaseOrder::where('id', $id)
      ->with('user')
      ->first();

      $product = PurchaseItem::where('purchase_order_id', $id)
      ->with(['listItem.product', 'listItem'])
      ->get();
      
      // dd($product);
      return view('dashboard.order.detail', compact('data', 'product', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
