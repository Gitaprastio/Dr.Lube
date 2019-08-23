@extends('dashboard.sales.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Purchase Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
  </div>

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
              <th>Product</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Mangga Company</td>
              <td>Oli Coklat</td>
              <td>
                  <div>
                    <a href="#" class="btn btn-success">Confirm</a>
                    <a href="#" class="btn btn-danger">Reject</a>
                    <a href="details.html" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Jeruk Company</td>
              <td>Oli Mocca</td>
              <td>
                  <div>
                      <a href="#" class="btn btn-success">Confirm</a>
                      <a href="#" class="btn btn-danger">Reject</a>
                      <a href="#" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Coconut Company</td>
              <td>Oli Markisa</td>
              <td>
                  <div>
                      <a href="#" class="btn btn-success">Confirm</a>
                      <a href="#" class="btn btn-danger">Reject</a>
                      <a href="#" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
            <tr>
              <td>4</td>
              <td>Banana Company</td>
              <td>Oli Melon</td>
              <td>
                  <div>
                      <a href="#" class="btn btn-success">Confirm</a>
                      <a href="#" class="btn btn-danger">Reject</a>
                      <a href="#" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
            <tr>
              <td>5</td>
              <td>Avocado Company</td>
              <td>Oli Vanilla</td>
              <td>
                  <div>
                      <a href="#" class="btn btn-success">Confirm</a>
                      <a href="#" class="btn btn-danger">Reject</a>
                      <a href="#" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
            <tr>
              <td>6</td>
              <td>Durian Company</td>
              <td>Oli Strawberry</td>
              <td>
                  <div>
                      <a href="#" class="btn btn-success">Confirm</a>
                      <a href="#" class="btn btn-danger">Reject</a>
                      <a href="#" class="btn btn-info">Details</a>
                  </div>
              </td>
            </tr>
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