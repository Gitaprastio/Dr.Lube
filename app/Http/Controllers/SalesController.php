<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Model\DetailUser;
use App\Model\PurchaseOrder;
use App\Model\PurchaseItem;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $title = "Purchase Order";

      $purchases = PurchaseOrder::
      where('status', 1)
      ->with('user')
      ->orderBy('created_at', 'asc')
      ->get();

      // dd($purchases);
      return view('dashboard.sales.index', compact('title','purchases'));
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
      $title = "Purchase Order Detail";
      $data = PurchaseOrder::where('id', $id)
      ->with('user')
      ->first();

      $product = PurchaseItem::where('purchase_order_id', $id)
      ->with(['listItem.product', 'listItem'])
      ->get();
      
      // dd($product);
      return view('dashboard.sales.detail', compact('data', 'product', 'title'));
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
    
    public function accept($id)
    {
        $data = PurchaseOrder::find($id);
        $data->status = 2;
        $data->save();
        return redirect()->route('sales.index')->with('alert','Purchase has been approved!');
    }
    
    public function reject($id)
    {
        $data = PurchaseOrder::find($id);
        $data->status = 90;
        $data->save();
        return redirect()->route('sales.index')->with('alert','Purchase has been Rejected!');
    }
}
