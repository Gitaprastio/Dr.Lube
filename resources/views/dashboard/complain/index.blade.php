@extends('dashboard.complain.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">
    @if(Session::has('alert-success'))
    <div class="alert alert-success">
        <strong>{{ \Illuminate\Support\Facades\Session::get('alert-success') }}</strong>
    </div>
  @endif
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Complaint Dashboard</h1>
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
              <th>Subject</th>
              <th>Description</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach($data as $d)
            
            <tr>
              <td>{{$no++}}</td>
              <td>{{$d->user->detailUser->organization_name}}</td>
              <td>{{$d->subject}}</td>
              <td>{{$d->complaint_desc}}</td>
              <td>
                <div>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#previewModal">Preview</button>
                    <button class="btn btn-info" data-toggle="modal" data-target="#reply">Reply</button>
                    <a href="#" class="btn btn-success">Done</a>

                </div>
              </td>
            </tr>
            {{-- preview--}}
            <div class="modal" tabindex="-1" role="dialog" id="previewModal">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Complaint Preview</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <label style="font-weight:bold" for="description">Subject :</label>
                      <p > {{$d->subject}}</p>
                      <label style="font-weight:bold" for="description">Description :</label>
                      <p>{{$d->complaint_desc}}</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div> 
              {{-- end-preview --}}
              <div class="modal" tabindex="-1" role="dialog" id="reply">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Complaint Reply</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form class="text-center border border-light p-5" action="{{route('user.reply',$d->id)}}" method="post" enctype="multipart/form-data">
                          @csrf
                          <label for="pic">PIC</label>
                          <input class="form-control" type="text" name="pic" placeholder="pic number">
                          <label for="pic">Date</label>
                          <input class="form-control" type="text" name="date_to_meet" placeholder="date to meet">
                          <button class="btn btn-info btn-block" type="submit">Send</button>
                        </form> 
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div> 
              
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