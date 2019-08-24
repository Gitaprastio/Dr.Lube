@extends('dashboard.sales.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Catatan Pembelian</h6>
    </div>
    <div class="card-body">
      <p>
        Company : {{$data->user->name}} <br> 
        Purchase Date  : {{date("d F Y G:i:s", strtotime($data->created_at))}} <br> 
        Shipping Date : {{$data->shipping_date}} <br> 
        Shipping Address : {{$data->shipping_address}} <br> 
        Total Cost : Rp. {{number_format($data->amount,3,',','.')}} <br> 
      </p>
    </div>
  </div>

  @foreach ($product as $item)
      
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Product</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$item->listItem->product->product_name}}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Price (per Litre)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{number_format($item->listItem->cost,3,',','.')}}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Quantities</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$item->quantity}} Litre</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Cost</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  @php
                      $total = $item->listItem->cost * $item->quantity;
                  @endphp
                  <div class="h6 mb-0 mr-3 font-weight-bold text-gray-800">Rp. {{number_format($total,3,',','.')}}</div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endforeach

  <div class="row">
    <div class="col-sm-6">
      <form action="{{ route('sales.accept', $data->id) }}" method="POST">
          {{ csrf_field() }}
          {{ method_field('PUT') }}
          <button class="w-100 btn btn-success" title="Confirm Purchase Order" onclick="return confirm('Confirm purchase order?')">Confirm</button>
      </form>
    </div>
    <div class="col-sm-6">
      <form action="{{ route('sales.reject', $data->id) }}" method="POST">
          {{ csrf_field() }}
          {{ method_field('PUT') }}
          <button class="w-100 btn btn-danger" title="Reject Purchase Order" onclick="return confirm('Reject purchase order?')">Reject</button>
      </form>
    </div>
  </div>
</div>

@endsection
@section('js')
<script>
// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable();
});

</script>
@endsection