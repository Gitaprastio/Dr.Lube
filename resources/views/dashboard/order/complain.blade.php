@extends('dashboard.order.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Complaint Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Complaint Form</h6>
    </div>
      <form class="text-center border border-light p-5" action="{{route('user.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        {{-- <p class="h4 mb-4">Complaint Form</p> --}}
    
        <!-- Name -->
        <!-- <input type="text" id="defaultContactFormName" class="form-control mb-4" placeholder="Organization"> -->
    
        <!-- Subject -->
        <label>Subject</label>
        <select class="browser-default custom-select mb-4" name="subject">
            <option value="" disabled selected>Choose option</option>
            <option value="Feedback">Feedback</option>
            <option value="Report">Report</option>
            <option value="Request">Request</option>
            <option value="Other">Other</option>
        </select>
    
        <!-- Message -->
        <div class="form-group">
            <textarea class="form-control rounded-0" id="exampleFormControlTextarea2" rows="3" placeholder="Complaint Detail" name="complaint_desc"></textarea>
        </div>
    
        <!-- Copy -->
        <div class="custom-control custom-checkbox mb-4">
            <input type="checkbox" class="custom-control-input" id="defaultContactFormCopy">
            <label class="custom-control-label" for="defaultContactFormCopy">Send me a copy of this message</label>
        </div>
    
        <!-- Send button -->
        <button class="btn btn-info btn-block" type="submit">Send</button>
    
    </form>
      
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