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

  <form method="POST" action="#">
  @csrf
    <div class="row" id="formPurchaseOrder">
      <div id="1formProduct" class="col-lg-6 animated--grow-in">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Product</h6>
            <a href="#" role="button" class="test2" id="1">
              <i class="fas fa-times fa-lg fa-fw text-danger"></i>
            </a>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="1product">Product</label>
              <select name="items[]" select class="form-control" id="1product">
                <option disabled selected> -- SELECT PRODUCT -- </option>
                @foreach ($products as $row)
                <option value="{{$row->id}}">{{$row->product_name}}</option>
                @endforeach
              </select>
              <input name="list_id[]" class="form-control" id="1listId" value="" type="hidden">
              <small id="1productHelp" class="form-text text-muted">Choosen product</small>
            </div>
            <div class="form-group">
              <label for="price1">Price</label>
              <input name="price[]" class="price-form form-control" id="1price" value="0" type="hidden">
              <input class="form-control" id="1priceFormatted" value="Rp. 0,00" disabled>
              <small id="1priceHelp" class="form-text text-muted">Price per litre</small>
            </div>
            <div class="form-group">
              <label for="1quantity">Quantity</label>
              <input name="quantity[]" type="number" class="form-control" id="1quantity" placeholder="Kuantitas">
              <small id="1quantityHelp" class="form-text text-muted">Quantity(litre)</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    <div class="modal fade" id="submitForm" tabindex="-1" role="dialog" aria-labelledby="submitForm" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="submitFormLabel">Are you sure you want to submit?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="shipping_address">Shipping Address</label>
              <textarea class="form-control" id="shipping_address" rows="3" required disabled>Address</textarea>
            </div>
        
            <div class="form-group">
              <label for="total_price">Price</label>
              <input class="form-control" id="total_price" value="" disabled type="hidden">
              <input class="form-control" id="total_price_currency" value="" disabled>
              <small id="totalPriceHelp" class="form-text text-muted">Total price</small>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-success" type="button">Edit</button>
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6 ">
        <div class="form-group row mb-0">
          <a id="submit-form" class="w-100 btn btn-primary" href="#" data-toggle="modal" data-target="#submitForm">
            Submit Purchase Order
          </a>
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
var counter = 1;
var total = 0;

var option = "<option disabled selected> -- SELECT PRODUCT -- </option>"

var product = <?php echo json_encode($product);?>;
// var obj = JSON.parse(product);
console.log(product.cost);
var cost = product.cost;
var list_id = product.list_id;

jQuery.each( product.name, function( i, val ) {
  option = option + "<option value=" + product.id[i] +">"+ product.name[i] +"</option>"
  // console.log(product.id[i]);
  // console.log(product.name[i]);
});
console.log(option);


$( "#newForm" ).click(function(e) {
  e.preventDefault;
  counter++;
  var form = '<div id="'+counter+'formProduct" class="col-lg-6 animated--grow-in"><div class="card shadow mb-4"><div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"><h6 class="m-0 font-weight-bold text-primary">Product</h6><a href="#" role="button" class="test2" id="'+counter+'"><i class="fas fa-times fa-lg fa-fw text-danger"></i></a></div><div class="card-body"><div class="form-group"><label for="'+counter+'product">Product</label><select class="form-control" id="'+counter+'product">'+option+'</select><input name="list_id[]" class="form-control" id="'+counter+'listId" value="" type="hidden"><small id="'+counter+'productHelp" class="form-text text-muted">Choosen product</small></div><div class="form-group"><label for="price1">Price</label><input class="price-form form-control" id="'+counter+'price" value="0" type="hidden"><input class="form-control" id="'+counter+'priceFormatted" value="Rp. 0,00" disabled><small id="'+counter+'priceHelp" class="form-text text-muted">Price per litre</small></div><div class="form-group"><label for="'+counter+'quantity">Quantity</label><input type="number" class="form-control" id="'+counter+'quantity" placeholder="Kuantitas"><small id="'+counter+'quantityHelp" class="form-text text-muted">Quantity(litre)</small></div></div></div></div>'
  $( "#formPurchaseOrder" ).append( form );
  console.log(counter);
});

$(document).on('click', '.test2' , function(e) {
  e.preventDefault;
  var remove = '#formProduct'+$(this).attr('id');
  $(this).first().parents().eq(2).remove();
  console.log(remove);
});

$(document).on('change', 'select' , function(e) {

  // console.log(e.target.selectedIndex);
  var index = e.target.selectedIndex - 1;
  // console.log(cost);
  // console.log(cost[index]);

  var selectId = $(this).attr('id');
  console.log(selectId);
  var selectId = parseInt(selectId, 10);

  console.log(selectId);

  var currency = addCommas(cost[index]);
  var currency = "Rp. " + currency;
  // console.log(currency);
  
  $("#"+selectId+"listId").attr('value', list_id[index]);
  $("#"+selectId+"priceFormatted").attr('value', currency);
  $("#"+selectId+"price").attr('value', cost[index]);

});

$( "#submit-form" ).click(function(e) {
  $(".price-form").each(function() {
    
    var inputId = $(this).attr('id');
    console.log(inputId);
    
    // console.log(selectId);
    var inputId = parseInt(inputId, 10);
    console.log(inputId);
    var quant = $("#"+inputId+"quantity").val();

    console.log(quant);

    total += Number($(this).attr('value')) * quant;
  });
  var currency = addCommas(total);
  $("#total_price_currency").val(currency);
  $("#total_price").val(total);
});

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}

</script>
@endsection