<!DOCTYPE html>
<html>

  <head>
    <title>
      EzPump | Sales Dashboard
    </title>
    <meta charset="utf-8">
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet"/>
    <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet"/>
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js">
    </script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
  </head>
  <Style>
    .input-group>.form-control, .input-group>.form-select, .input-group{ z-index:
    2; float: left; height: 38px; color: #333333; border-radius: 5px; font-size:
    15px; border: 1px solid rgba(0, 0, 0, 0.3); box-shadow: inset 0 1px 4px
    rgba(0, 0, 0, 0.2); } .select2-container .select2-selection--single{ color:
    #333333; border-radius: 5px; font-size: 15px; border: 1px solid rgba(0,
    0, 0, 0.3); box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2); display: block;
    user-select: none; -webkit-user-select: none; } .border-danger { border-color:
    #2a2d93 !important; } .text-danger { color: #2a2d93 !important; } .navbar{
    display: contents !important; } .btn-danger { color: #fff; background-color:
    #2a2d93 !important; border-color: #2a2d93 !important; } .logo { width:
    100px; /* Adjust dimensions as needed */ height: 100px; background-repeat:
    no-repeat; } .logo-icon{ width: 55px; /* Adjust dimensions as needed */
    } .text-bold{ font-weight:bold; } th, tr{ border-color: inherit; border-style:
    solid; border-width: 0.1px; } .table>:not(:last-child)>:last-child>* {
    border-bottom-color: currentColor; background: yellow; } .table>:not(caption)>*>*
    { padding: .5rem .2rem; } @media (min-width: 768px) { .row-cols-md-8>*
    { flex: 0 0 auto; width: 12.5%; } } .navbar{ display: contents !important;
    } .btn-danger { color: #fff; background-color: #2a2d93 !important; border-color:
    #2a2d93 !important; } .logo { width: 100px; /* Adjust dimensions as needed
    */ height: 100px; background-repeat: no-repeat; } .logo-icon{ width: 55px;
    /* Adjust dimensions as needed */ } .text-bold{ font-weight:bold; } th,
    tr{ border-color: inherit; border-style: solid; border-width: 0.1px; }
    .table>:not(:last-child)>:last-child>* { border-bottom-color: currentColor;
    background: yellow; } .table>:not(caption)>*>* { padding: .5rem .2rem;
    } .navbar>.container-fluid{ height: 70px; background: white; } input {
    width: 80px; border: none; } @media (min-width: 768px) { .row-cols-md-8>*
    { flex: 0 0 auto; width: 12.5%; } } [type=button]:not(:disabled), [type=reset]:not(:disabled),
    [type=submit]:not(:disabled), button:not(:disabled) { cursor: pointer;
    border: groove; border-radius: 8px; } .card-body { flex: 1 1 auto; padding:
    .5rem .5rem; }
  </Style>
  @php
use App\Models\Customer;
@endphp
  <body>
    <!--wrapper-->
    <div class="wrapper">
      <!--start page wrapper -->
      @include('layouts.header')
      <!--start page wrapper -->
      <div class="page-content">
        <div class="row">
          <div class="col-xl-9 mx-auto">
            <div class="card border-top border-0 border-4 border-danger">
              <div class="card-body ">
                <div class="card-title text-center">
                  <img class="logo" src="assets/images/{{$logoProfile}}" alt="Logo">
                </div>
                @php use App\Models\saledata; $Amount = 0; @endphp
                <div class="card">
                  <div class="card-body">
                    <table class="table mb-0 table-hover">
                      <thead>
                        <tr>
                          <th scope="col">
                            ID
                          </th>
                          <th scope="col">
                            Nozel ID
                          </th>
                          <th scope="col">
                            DATE TIME
                          </th>
                          <th scope="col">
                            QTY
                          </th>
                          <th scope="col">
                            AMOUNT
                          </th>
                          <th scope="col">
                            RATE
                          </th>
                          <th scope="col">
                            Product
                          </th>
                          <th scope="col">
                            Mode of Payment
                          </th>
                          <th scope="col">
                            Customer
                          </th>
                          <th scope="col">
                            Vehicle
                          </th>
                          <th scope="col">
                            Print
                          </th>
                          <th scope="col">
                            Save
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($filters as $filter) @php $Amount += $filter->amt; @endphp
                        <tr>
                          <form action="/" method="post">
                            @csrf
                            <td>
                              <input name="id" value="{{$filter->id}}" disabled>
                            </td>
                            <td>
                              {{$filter->FC_NZNo }}
                            </td>
                            <td>
                              {{$filter->tdate}}
                            </td>
                            <td>
                              {{$filter->qty}}
                            </td>
                            <td>
                              {{$filter->amt}}
                            </td>
                            <td>
                              {{$filter->rate}}
                            </td>
                            @php $itmnames = Saledata::join('PRODUCT', 'PRODUCT.ICODE', '=', 'Saledata.icode')
                            ->where('Saledata.icode', $filter->icode) ->select('PRODUCT.ITMNAME') ->get();
                            $cust = customer::where('id', $filter->customer_id)->value('Des');
                            @endphp @foreach ($itmnames as $itmname)
                            <td>
                              {{ $itmname->ITMNAME }}
                            </td>
                            @endforeach
                            <td>
                              {{$Payments}}
                            </td>
                            <td>
                            @if (($cust))
                            <input type="text" name="customer" value="{{$cust}}" disabled>
                            @else
                            <input type="text" name="customer" value="Walk-in" disabled>
                            @endif
                            </td>
                            <td>
                              <input type="text" name="reg_no" value="{{$filter->RegNO}}" disabled>
                            </td>
                            <td>
                              <a href="">
                                <span class="badge bg-secondary">
                                  Print
                                </span>
                              </a>
                            </td>
                            <td>
                              <span class="badge ">
                                <input type="submit" disabled>
                              </span>
                            </td>
                          </form>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">
                  </label>
                  <div class="col-sm-9">
                    <P class="btn btn-info">
                      <b>
                        Total Amount: RS. {{$Amount}}
                      </b>
                    </P>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--end row-->
        </div>
      </div>
      <!--end page wrapper -->
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js">
    </script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js">
    </script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js">
    </script>
    <script src="assets/plugins/select2/js/select2.min.js">
    </script>
    <script>
      $('.single-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%': 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
      });
      $('.multiple-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%': 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
      });
    </script>
    <!--app JS-->
    <script src="assets/js/app.js">
    </script>
  </body>

</html>
