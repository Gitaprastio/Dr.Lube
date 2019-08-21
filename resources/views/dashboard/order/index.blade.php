@extends('dashboard.main')
@section('css')
    
@endsection
@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800">Purchase Order</h1>
  </div>

  <button id="newForm" class="mb-3 d-none d-sm-inline-block btn btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add new item</button>
  <!-- Content Row -->

  <form method="POST" action="">
  @csrf
    <div class="row" id="formPurchaseOrder">
      
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6 ">
        <div class="form-group row mb-0">
          <button type="submit" class="w-100 btn btn-primary">
              Submit Purchase Order
          </button>
        </div>
      </div>
    </div>

  </form>

</div>
@endsection
@section('modal')
    
@endsection
@section('js')
<script>
var counter = 0;

$( "#newForm" ).click(function(e) {
  e.preventDefault;
  counter++;
  var form = '<div id="formProduct'+counter+'" class="col-lg-6 animated--grow-in"><div class="card shadow mb-4"><div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"><h6 class="m-0 font-weight-bold text-primary">Product</h6><a href="#" role="button" class="test2" id="'+counter+'"><i class="fas fa-times fa-lg fa-fw text-danger"></i></a></div><div class="card-body"><div class="form-group"><label for="product'+counter+'">Product</label><select class="form-control" id="product'+counter+'"><option>Oli 1</option><option>Oli 2</option><option>Oli 3</option><option>Oli 4</option><option>Oli 5</option></select><small id="productHelp'+counter+'" class="form-text text-muted">Choosen product</small></div><div class="form-group"><label for="price1">Price</label><input class="form-control" id="price'+counter+'" value="Rp. 15.000" disabled><small id="priceHelp'+counter+'" class="form-text text-muted">Price per litre</small></div><div class="form-group"><label for="quantity'+counter+'">Quantity</label><input type="number" class="form-control" id="quantity'+counter+'" placeholder="Kuantitas"><small id="quantityHelp'+counter+'" class="form-text text-muted">Quantity(litre)</small></div></div></div></div>'
  $( "#formPurchaseOrder" ).append( form );
  console.log(counter);
});

$(document).on('click', '.test2' , function(e) {
  e.preventDefault;
  var remove = '#formProduct'+$(this).attr('id');
  $(this).first().parents().eq(2).remove();
  console.log(remove);
});



</script>
@endsection