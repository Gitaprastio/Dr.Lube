@extends('dashboard.sales.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Sales Dashboard</h1>
  </div>

  @if (session('alert'))
  <div class="alert alert-primary" role="alert">
    {{ session('alert') }}
  </div>
  @endif

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Purchase Order</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Organization</th>
              <th>Purchase Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($purchases as $purchase)
                
            <tr>
              <td>{{$no}}</td>
              <td>{{$purchase->user->name}}</td>
              <td>{{date("d F Y G:i:s", strtotime($purchase->created_at))}}</td>
              <td>
                  <div>
                    <form class="d-inline" action="{{ route('sales.accept', $purchase->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <button class=" btn btn-success" title="Confirm Purchase Order" onclick="return confirm('Confirm purchase order?')">Confirm</button>
                    </form>
                    <form class="d-inline" action="{{ route('sales.reject', $purchase->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <button class=" btn btn-danger" title="Reject Purchase Order" onclick="return confirm('Reject purchase order?')">Reject</button>
                    </form>
                    <a href="{{ route('sales.show', $purchase->id) }}" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>

            @php
                $no++
            @endphp
            @endforeach
            
          </tbody>
        </table>
      </div>
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