<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\PurchaseOrder;
use App\Model\User;
use App\Model\ProductAgreement;
use App\Model\ListItem;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $title = "Purchase Order";

      $user = Auth::user();
      $uid = Auth::id();

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

      return view('dashboard.order.index', compact('title','products', 'product', 'product_name', 'product_id', 'cost'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
