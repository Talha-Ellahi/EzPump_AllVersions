<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer and Vehicle Information</title>
  <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    @vite([
        'resources/js/app.js', 
        ])
</head>

<body>
  <div id="app">
    @include('layouts.header')

    <div id="app"><index/></div>
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.select2').select2({
        ajax: {
          url: function (params) {
            if (this[0].id === 'customerSelect') {
              return '/api/getCustomers';
            } else if (this[0].id === 'vehicleSelect') {
              return '/api/getVehicles';
            } else if (this[0].id === 'pumpSelect') {
              return '/api/getPumps';
            }
          },
          data: function (params) {
            if (this[0].id === 'vehicleSelect') {
              return {
                customer_id: $('#customerSelect').val(),
                search: params.term
              };
            } else if (this[0].id === 'pumpSelect') {
              var selectedVehicle = $('#vehicleSelect').select2('data')[0].data;
              return {
                icode: selectedVehicle.icode,
                search: params.term
              };
            }
            return { search: params.term };
          },
          processResults: function (data) {
            var sel = this.$element[0].id;
            if (sel == 'vehicleSelect') {
              return {

                results: data.map(function (item) {
                  return {
                    id: item.id,
                    text: item.RegNO + (item.VehBlocked ? " (Blocked) " : ""),
                    data: item,
                    disabled: item.VehBlocked,
                  };
                })
              };
            };
            return {

              results: data.map(function (item) {
                return {
                  id: item.id,
                  text: item.Des || item.RegNO || item.id,
                  data: item,
                };
              })
            };
          }
        }

      });

      $('#customerSelect').on('change', function () {
        resetForm();
      });

      $('#vehicleSelect').on('change', function () {
        resetFormValues();
        try {
          var selectedVehicle = $('#vehicleSelect').select2('data')[0].data;

        } catch {
          return;
        }
        var sRate = selectedVehicle.SRATE;
        var icode = selectedVehicle.icode;
        var balance = selectedVehicle.CreditLimit - selectedVehicle.LimitUsed;

        $('#creditLimit').val(balance);
        $('#productQtyLimit').val((balance / sRate).toFixed(2));

        if (balance && sRate) {
          $('#submitBtn').show();
        }

        // Load product name
        if (icode) {
          $.ajax({
            url: '/api/getProductName',
            method: 'GET',
            data: { icode: icode },
            success: function (data) {
              $('#productName').val(data.ITMNAME);
            }
          });
        }
      });

      $('#submitBtn').click(function () {
        var vehicleId = $('#vehicleSelect').val();
        var pumpId = $('#pumpSelect').val();
        var qty = $('#productQtyLimit').val();
        var productQty = $('#productQty').val();
        if(productQty=='' || productQty==0){
          alert("Enter valid Amount");
          return;
        }
        if(productQty > qty){
          alert("Limit exceeded");
          return;
        }
        $.ajax({
          url: '/api/addCreditSlip',
          method: 'POST',
          data: {
            vehicle_id: vehicleId,
            pump_id: pumpId,
            qty: productQty,
            _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            alert('Credit Slip Added Successfully');
          }
        });
      });

      function resetForm() {
        $('#vehicleSelect').val(null).trigger('change');
        resetFormValues();
      }

      function resetFormValues() {
        $('#pumpSelect').val(null).trigger('change');
        $('#productName').val('');
        $('#creditLimit').val('');
        $('#productQtyLimit').val('');
        $('#submitBtn').hide();
      }
    });
  </script>
</body>

</html>