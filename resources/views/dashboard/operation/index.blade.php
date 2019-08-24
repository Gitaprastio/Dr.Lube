@extends('dashboard.operation.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Operation Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
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
                    <form class="d-inline" action="{{ route('operation.send', $purchase->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <button class="btn btn-success" title="Send Purchase Order" onclick="return confirm('Send purchase order?')">Send</button>
                    </form>
                    <a href="{{ route('sales.show', $purchase->id) }}" class="btn btn-info">Cetak Invoice</a>
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