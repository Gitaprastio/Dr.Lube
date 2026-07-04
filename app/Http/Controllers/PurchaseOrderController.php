<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Model\PurchaseOrder;
use App\Model\PurchaseItem;
use App\Model\DetailUser;
use App\Model\ListItem;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

      $listItems = $this->authorizedListItems($uid)->get();
      $products = $listItems->pluck('product');

      $product = [
          'cost' => $listItems->pluck('cost'),
          'name' => $listItems->pluck('product.product_name'),
          'id' => $listItems->pluck('product.id'),
          'list_id' => $listItems->pluck('id'),
      ];

      return view('dashboard.order.index', compact('title','user','products', 'product'));
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
      $uid = Auth::id();

      $this->validate($request, [
        'list_id' => 'required|array|min:1',
        'list_id.*' => 'required|integer|distinct',
        'quantity' => 'required|array|min:1',
        'quantity.*' => 'required|integer|min:1',
        'shipping_address' => 'nullable|string|max:255',
      ]);

      $listIds = array_map('intval', $request->input('list_id', []));
      $quantities = array_map('intval', $request->input('quantity', []));

      if (count($listIds) !== count($quantities)) {
        throw ValidationException::withMessages([
          'quantity' => ['Each selected product must include a matching quantity.'],
        ]);
      }

      $listItems = $this->authorizedListItems($uid)
        ->whereIn('id', $listIds)
        ->get()
        ->keyBy('id');

      if ($listItems->count() !== count($listIds)) {
        throw ValidationException::withMessages([
          'list_id' => ['One or more selected products are not available under your agreement.'],
        ]);
      }

      $amount = 0;
      $items = [];

      foreach ($listIds as $key => $listId) {
        $listItem = $listItems->get($listId);
        $quantity = $quantities[$key];

        if ($quantity > $listItem->quantity) {
          throw ValidationException::withMessages([
            'quantity' => ['Quantity exceeds the contracted quantity for '.$listItem->product->product_name.'.'],
          ]);
        }

        $amount += $listItem->cost * $quantity;
        $items[] = [
          'list_item_id' => $listId,
          'quantity' => $quantity,
        ];
      }

      $user = DetailUser::where('user_id', $uid)->first();
      $shippingAddress = trim((string) $request->input('shipping_address'));

      if ($shippingAddress === '') {
        $shippingAddress = $user ? $user->address : '';
      }

      if ($shippingAddress === '') {
        throw ValidationException::withMessages([
          'shipping_address' => ['Shipping address is required.'],
        ]);
      }

      DB::transaction(function () use ($uid, $amount, $shippingAddress, $items) {
        $po = new PurchaseOrder;
        $po->status = 1;
        $po->amount = $amount;
        $po->shipping_address = $shippingAddress;
        $po->shipping_date = now();
        $po->user_id = $uid;
        $po->save();

        foreach ($items as $item) {
          $data = new PurchaseItem;
          $data->purchase_order_id = $po->id;
          $data->list_item_id = $item['list_item_id'];
          $data->quantity = $item['quantity'];
          $data->save();
        }
      });

      return redirect()->route('purchase.index')->with('alert','Purchase has been processed!');
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
      ->where('user_id', Auth::id())
      ->with('user')
      ->firstOrFail();

      $product = PurchaseItem::where('purchase_order_id', $data->id)
      ->with(['listItem.product', 'listItem'])
      ->get();

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

    private function authorizedListItems($userId)
    {
      return ListItem::with('product')
        ->whereHas('agreement', function($q) use ($userId){
          $q->where('user_id', '=', $userId);
        });
    }
}
