@extends('dashboard.complain.main')
@section('css')
    
@endsection
@section('content')
  <div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Seller Dashboard</h1>
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
              <th>Subject</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($complaints as $complaint)
            <tr>
              <td>{{$loop->iteration}}</td>
              <td>{{optional($complaint->user)->name}}</td>
              <td>{{$complaint->subject}}</td>
              <td>
                <div>
                    <span class="badge badge-primary">Status {{$complaint->status}}</span>
                </div>
              </td>
            </tr>
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
