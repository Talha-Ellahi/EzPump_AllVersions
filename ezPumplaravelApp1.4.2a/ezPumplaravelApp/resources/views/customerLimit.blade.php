@extends('layouts.app')
@section('content')
  <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
<style>
    /* General Form Styling */
    .new_form_id .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .new_form_id label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #2d3748;
      font-size: 0.9rem;
    }

    .new_form_id .select2-container--default .select2-selection--single,
    .form-control {
      width: 100%;
      height: calc(2.25rem + 2px);
      padding: 0.375rem 0.75rem;
      font-size: 0.9rem;
      line-height: 1.6;
      color: #4a5568;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #e2e8f0;
      border-radius: 0.375rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* Focus States */
    .new_form_id .select2-container--default.select2-container--focus .select2-selection--single,
    .form-control:focus {
      border-color: #4299e1;
      outline: 0;
      box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    /* Read-only Fields */
    .new_form_id input[readonly] {
      background-color: #e9e9e9;
      border-color: #e2e8f0;
      cursor: not-allowed;
    }

    /* Select2 Customization */
    .new_form_id .select2-container--default .select2-selection--single {
      height: auto;
      min-height: 42px;
    }

    .form-control {
      min-height: 42px;
    }

    .new_form_id .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 36px;
      padding-left: 12px;
    }

    .new_form_id .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 42px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .new_form_id .col-md-6 {
        margin-bottom: 1rem;
      }

      .new_form_id label {
        font-size: 0.85rem;
      }
    }

    /* Form Group Hover Effect */
    .new_form_id .form-group:hover label {
      color: #4299e1;
    }

    /* Read-only Field Indicator */
    .new_form_id input[readonly]+label::after {
      content: "🔒";
      margin-left: 0.5rem;
      opacity: 0.6;
    }



    .new_form_id .form-group {
      position: relative;
    }

    .new_form_id .form-group::after {
      font-family: 'Font Awesome 5 Free';
      position: absolute;
      right: 15px;
      top: 35px;
      color: #a0aec0;
      font-size: 14px;
    }

    .form-control:focus,
    .new_form_id .select2-container--default.select2-container--focus .select2-selection--single {
      box-shadow: unset;
      outline: 0;
    }

    .card-header {
      background: linear-gradient(45deg, #ef4523, #c91f1f);
    }

    .card-header h4 {
      margin-bottom: 0;
      color: black;
    }

    .new_form_id #customerSelect::after {
      content: "\f007";
    }

    /* user icon */
    .new_form_id #vehicleSelect::after {
      content: "\f1b9";
    }

    /* car icon */
    .new_form_id #pumpSelect::after {
      content: "\f136";
    }

    /* fuel icon */
  </style>
    <main class="py-4 main_wrapper">

      <div class="container">
        <div class="card">
          <div class="card-header bg-light">
            <h4>Check Customer</h4>
          </div>
          <div class="card-body">
            <div class="new_form_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customerSelect">Select Customer</label>
                    <select id="customerSelect" class="form-control select2"></select>
                  </div>
                </div>

                <div class="col-md-6">

                  <div class="form-group">
                    <label for="vehicleSelect">Select Vehicle</label>
                    <select id="vehicleSelect" class="form-control select2"></select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="pumpSelect">Select Pump</label>
                    <select id="pumpSelect" class="form-control select2"></select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="creditLimit">available credit (PKR)</label>
                    <input type="text" id="creditLimit" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="productQtyLimit">Available Limit(Ltrs)</label>
                    <input type="text" id="productQtyLimit" class="form-control" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="productQty">Purchase Qty (Litre)</label>
                    <input type="text" id="productQty" class="form-control">
                  </div>
                </div>

              </div>
              <div class="row justify-content-center">
                <div class="col-auto ">
                  <button id="submitBtn" class="btn btn-primary" style="display: none;">Submit</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2({
        ajax: {
          url: function(params) {
            if (this[0].id === 'customerSelect') {
              return '/api/getCustomers';
            } else if (this[0].id === 'vehicleSelect') {
              return '/api/getVehicles';
            } else if (this[0].id === 'pumpSelect') {
              return '/api/getPumps';
            }
          },
          data: function(params) {
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
            return {
              search: params.term
            };
          },
          processResults: function(data) {
            var sel = this.$element[0].id;
            if (sel == 'vehicleSelect') {
              return {

                results: data.map(function(item) {
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

              results: data.map(function(item) {
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

      $('#customerSelect').on('change', function() {
        resetForm();
      });

      $('#vehicleSelect').on('change', function() {
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
            data: {
              icode: icode
            },
            success: function(data) {
              $('#productName').val(data.ITMNAME);
            }
          });
        }
      });

      $('#submitBtn').click(function() {
        var vehicleId = $('#vehicleSelect').val();
        var pumpId = $('#pumpSelect').val();
        var qty = $('#productQtyLimit').val();
        var productQty = $('#productQty').val();
        if (productQty == '' || productQty == 0) {
          alert("Enter valid Amount");
          return;
        }
        if (productQty > qty) {
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
          success: function(response) {
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
@endsection