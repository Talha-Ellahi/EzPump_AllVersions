<!DOCTYPE html>
<html>

<head>
    <title> EzPump | Sales Dashboard
    </title>
    <meta charset="utf-8">


    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />


    <link href="assets/css/new_style.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
</head>
<Style>
    .payment-card-mobile {
        display: flex;
        align-items: center;
    }

    .payment-card-mobile .img {
        max-width: 50%;
    }

    .payment-card-mobile .card-text {
        margin-left: 10px;
        font-size: 15px;
    }

    .bg-disabled {
        background-color: #d7d7d7 !important;
    }

    .input-group>.form-control,
    .input-group>.form-select,
    .input-group {

        z-index: 2;
        float: left;
        height: 38px;
        color: #333333;
        border-radius: 5px;
        font-size: 15px;
        border: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .col-xl-9 {
        flex: 0 0 auto;
        width: 100%;
    }

    .btn-success {
        color: #fff;
        background-color: #15ca20;
        border-color: #15ca20;
        font-size: 7px;
        border: none;
    }

    .select2-container .select2-selection--single {

        color: #333333;
        border-radius: 5px;
        font-size: 15px;
        border: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2);
        display: block;
        user-select: none;
        -webkit-user-select: none;
    }

    .border-danger {
        border-color: #920a00 !important;
    }

    .text-danger {
        color: #ff0000 !important;
    }

    a {
        color: #0a0a0a !important;
    }

    .card-title {
        margin-bottom: -.5rem;
    }

    .navbar {
        display: contents !important;
    }

    .btn-danger {
        color: #fff;
        background-color: #2a2d93 !important;
        border-color: #2a2d93 !important;
    }

    .logo {
        width: 100px;
        /* Adjust dimensions as needed */
        height: 100px;
        background-repeat: no-repeat;
    }

    .logo-icon {
        width: 55px;
        /* Adjust dimensions as needed */

    }

    .text-bold {
        font-weight: bold;
    }

    th,
    tr {
        border-color: inherit;
        border-style: solid;
        border-width: 0.1px;
    }

    .table>:not(:last-child)>:last-child>* {
        border-bottom-color: currentColor;
        background: yellow;
    }

    .table>:not(caption)>*>* {
        padding: .5rem .2rem;
    }

    .navbar>.container-fluid {
        height: 70px;
        background: white;
    }

    input {
        width: 80px;
        border: none;
    }

    @media (min-width: 768px) {
        .row-cols-md-8>* {
            flex: 0 0 auto;
            width: 12.5%;
        }
    }

    [type=button]:not(:disabled),
    [type=reset]:not(:disabled),
    [type=submit]:not(:disabled),
    button:not(:disabled) {
        cursor: pointer;
        border: groove;
        border-radius: 8px;
    }

    .card-body {
        flex: 1 1 auto;
        padding: .5rem .5rem;
    }

    .card-title {
        margin-bottom: -3.5rem;
    }


    .card.new_sale_id {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card.new_sale_id.openable:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .card.new_sale_id.bg-disabled {
        /*   opacity: 0.6;
    pointer-events: none;*/
        /*    background-color: #f0f0f0 !important;*/
    }

    .new_sale_id .card-body ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .new_sale_id .card-body ul li {
        display: flex;
        align-items: center;
        padding: 8px 0;
        font-size: 15px;
        color: #333;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
    }

    .new_sale_id .card-body ul li:last-child {
        border-bottom: none;
    }

    .new_sale_id .card-body ul li b {
        min-width: 100px;
        display: inline-block;
        font-weight: 600;
        color: #555;
    }

    .new_sale_id .card-body ul li span {

        display: flex;
        align-items: center;
    }

    .new_sale_id .card-body ul li i {
        color: #ef4523;
        min-width: 20px;
    }

    /* Button Styling */
    .new_sale_id .card-body .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        transition: background-color 0.3s ease;
        width: 100%;
        border: unset;
        background: #1f1d1e;
    }

    .form-select:focus,
    .form-control:focus {
        box-shadow: unset;
        border-color: unset;
    }

    .new_sale_id .card-body .btn:hover {
        background-color: #0056b3;
    }
</Style>

<body class="bg-login">
    <!-- Trigger button -->

    <!--wrapper-->
    <div class="wrapper">

        <!--start page wrapper -->

        <div class="page-content_id">

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div
                                class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-danger"><i class="bx bxs-message-square-x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-danger"><b>{{ $error }}</b></h6>
                                        <div>Please Use Valid Data</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endforeach
                    @endif
                    <div class=" new_card">
                        @php
                            use App\Models\saledata;
                            use App\Models\paymentmethod;
                            use App\Models\Customer;
                            use App\Models\Vehicles;
                        @endphp


                        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-5" style="padding: 15px; display:none">
                            <div class="card-title text-center">
                                <img class="logo" src="assets/images/PSO_Logo.png" alt="Logo">
                                <h4 class="mb-5 mt-2 text-dark"><b>Card Sale</b></h4>
                            </div>

                            @foreach ($Payments as $payment)
                                <form action="<?php echo e(route('card_details')); ?>" method="post">
                                    @csrf
                                    <div class="col">
                                        <div class="main_box">
                                            <div class="inner-body">
                                                <div class="text-center">
                                                    <div class="rounded-circle mx-auto text-primary mb-3">
                                                        <img class="logo-icon"
                                                            src="assets/images/{{ $payment->logo_profile }}"
                                                            alt="Logo">
                                                    </div>
                                                    <h6 class="my-1 text-bold">{{ $payment->Des }}</h6>
                                                    <input style="display:none" type="text" id="userid"
                                                        name="userid" value="<?php echo e($payment->id); ?>">
                                                    <button type="submit"> Select Card</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endforeach

                        </div>
                        @if ($Sales->isEmpty())
                            <div class="alert alert-info text-center">
                                <strong>No Sales Found</strong>
                            </div>
                        @else

                        <div class="main_box">
                            <div class="inner-body">
                                <div class="row " id="sale-table">
                                    @foreach ($Sales as $sale)
                                        @php
                                            // dd($Products);

                                            $itmname = $Products->first(function ($p) use ($sale) {
                                                return $p->ICODE == $sale->icode;
                                            });
                                            $descriptions = PaymentMethod::where('id', $sale->p_mode ?? 1)->first();
                                            $cust = customer::where('id', $sale->customer_id)->value('Des');
                                            $vehicles = Vehicles::where('customer_id', $sale->customer_id)->get();
                                            if ($sale->id == 14409) {
                                                // dd([$itmnames, $sale, $Products]);
                                            }
                                                        $pump = DB::table('PUMPS')
                                                            ->where('id', $sale->FC_NZNo)
                                                            ->first();
                                        @endphp

                            <div class="col-sm-12 col-md-4 col-lg-3 ">
                                            <div class="card new_sale_id mb-3 saleRow {{ $sale->bIsUpdated == 1 ? 'bg-disabled' : 'openable' }} ">
                                <form method="post">
                                                    <input type="hidden" name="product"
                                                        value="{{ $itmname->ITMNAME ?? 'N/A' }}">
                                <div class="card-body">


                                                        @if ($sale->bIsUpdated == 1)
                                                            <div class="badge bg-danger">
                                                                Disabled
                                            </div>
                                                        @else
                                                            <div class="badge bg-success">
                                                                Active
                                            </div>
                                                        @endif


                                                        <ul>
                                                            <li><b><i class="fa-regular fa-calendar-days"></i> Date:</b>
                                                                <span>{{ $sale->pdate }}</span></li>
                                                            <li><b><i class="fa-solid fa-boxes-stacked"></i> Qty:</b>
                                                                <span>{{ $sale->qty / 100 }}</span></li>
                                                            <li><b><i class="fa-solid fa-dollar-sign"></i> Amount:</b>
                                                                <span>{{ $sale->amt / 100 }}</span></li>
                                                            <li><b><i class="fa-solid fa-gas-pump"></i> Nozel ID:</b>

{{--                                                                <span>{{ isset($pump) ? ($pump->FC_NZNo??null) : $sale->FC_NZNo }}</span></li>--}}
                                                                <span>{{  $sale->FC_NZNo }}</span></li>

                                                            <li><b><i class="fa-solid fa-money-check-dollar"></i>
                                                                    Payment:</b>
                                                                <span>
                                                @if ($descriptions)
                                                                        {{ $descriptions->Des }}
                                                @else
                                                    N/A
                                                @endif
                                                                </span>
                                                            </li>
                                                            <li><b><i class="fa-solid fa-user"></i> Customer:</b>
                                                                <span>
                                                @if (is_null($sale->customer_id))
                                                    N/A
                                                @else
                                                    {{ $cust }}
                                                @endif
                                                                </span>
                                                            </li>
                                                            <li><b><i class="fa-solid fa-car"></i> Vehicle:</b>
                                                                <span>
                                                @if (is_null($sale->RegNO) || $sale->RegNO == 0 || $sale->RegNO == '0')
                                                    N/A
                                                                    @elseif ($sale->vid != null && $sale->vid != '')
                                                    {{ $vehicles->first(function ($v) use ($sale) {
                                                        return $v->id == $sale->vid;
                                                    })->RegNO }}
                                                @else
                                                    {{ $sale->RegNO }}
                                                @endif
                                                                </span>
                                                            </li>
                                                        </ul>

                                                        <div class="d-flex justify-content-end">
                                                            <form method="post" id="myForm">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $sale->id }}">
                                                                <button class="btn btn-primary" type="submit"
                                                                    name="save_type" value="onlyPrint">
                                                                    <i class="lni lni-printer"></i> Print
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        @endif

                    </div>


                </div>
                <!--end row-->

            </div>
        </div>

        <!--end page wrapper -->



        <!-- Modal -->
        <div class="modal fade " id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="paymentForm" method="post">
                            <input type="hidden" name="_token" value="MveM2e1Q1hqUeKMoyQJ27qPQVnQnmnqu83vK62iW">
                            <input type="hidden" name="id" id="saleId">
                            <input type="hidden" name="save_type" value="print" />
                            <div class="row">
                                <!-- <div class="col col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="sale_id">Sale ID</label>
                    <input type="text" class="form-control" id="sale_id" readonly>
                </div>
                </div> -->
                                <div class=" col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="datetime">Date/Time</label>
                                        <input type="text" class="form-control" id="datetime" readonly>
                                    </div>
                                </div>
                                <div class=" col-md-6 col-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="text" class="form-control" id="quantity" readonly>
                                    </div>
                                </div>


                                <div class=" col-md-6 col-6">
                                    <div class="form-group">
                                        <label for="product">Product</label>
                                        <input type="text" class="form-control" id="product" readonly>
                                    </div>
                                </div>
                                <div class=" col-md-6 col-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="text" class="form-control" id="price" readonly>
                                    </div>
                                </div>
                                <div class="col col-md-3 col-6">
                                    <div class="form-group">
                                        <label for="price">Total Price</label>
                                        <input type="text" class="form-control" id="discount" readonly>
                                    </div>
                                </div>
                                <div class="col col-md-3 col-6">
                                    <div class=" customer-select">
                                        <label for="customers">Customer</label>
                                        <select class="form-select form-select-sm mb-3 cust" name="customers"
                                            id="customers" style="display:none" aria-label=".form-select-sm example">
                                            <option value="">Walk in Customer</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col col-md-3 col-sm-6">

                                    <label for="reg_no">Vehicle</label>

                                    <div class="" id="reg_no_container">
                                        <input type="text" class="form-control mb-3 veh" name="reg_no"
                                            id="reg_no" placeholder="Enter Vehicle Registration">
                                    </div>

                                </div>
                            </div>
                            <!-- Payment Methods -->
                            <div class="form-group">
                                <label for="Payment_method">Payment Method</label>
                                <input type="hidden" name="Payment_method" id="Payment_method" />
                                <div class="d-none d-sm-block">

                                    <div class="row ">
                                        <!-- Payment method cards -->
                                        @foreach ($Payments as $payment)
                                            <div class="col-md-2 col-lg-2 col-3">
                                                <div class="card payment-card" data-value="{{ $payment->id }}"
                                                    data-customer-required="{{ $payment->Des == 'Credit Customer' }}">
                                                 @if($payment->file==null)

{{--                                                    <img class="card-img-top"--}}
{{--                                                        src="/assets/images/{{ $payment->logo_profile }}"--}}
{{--                                                        alt="Cash">--}}
                                                    @else
                                                    <img
                                                       src="{{ $payment->file }}"
                                                        alt="Sample Test Image">
                                                    @endif
                                                    <div class="card-body text-center">
                                                        <p class="card-text">{{ $payment->Des }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-block d-sm-none">

                                    <div class="row ">
                                        <!-- Payment method cards -->
                                        @foreach ($Payments as $payment)
                                            <div class="col-md-3 col-6">
                                                <div class="payment-card payment-card-mobile"
                                                    data-value="{{ $payment->id }}"
                                                    data-customer-required="{{ $payment->Des == 'Credit Customer' }}">
                                                    <img class="img"
                                                        src="{{ $payment->file }}"
                                                        alt="Cash">
                                                    <div class="card-body text-center">
                                                        <h3 class="card-text">{{ $payment->Des }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>



                        </form>
                    </div>
                    <div class="modal-footer d-none">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitPayment()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!--end wrapper-->
        <!--start switcher-->

        <!-- Bootstrap JS -->
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!--plugins-->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
        <script src="assets/plugins/select2/js/select2.min.js"></script>
        <script>
            function populateModal(e) {
                if (!$(e.target).is('button') && !$(e.target).is('a')) {} else {
                    return;
                }
                // Populate the modal with sale information
                var card = $(this).closest('.saleRow');

                var saleId = card.find('input[name="id"]').val();

                // var saleId = card.find('input[name="id"]').val();
                var quantity = card.find('.p-2:contains("Qty")').text().replace('Qty: ', '').trim();
                var datetime = card.find('.p-2:contains("Date")').text().replace('Date: ', '').trim();
                var product = card.find('input[name="product"]').val() // If you want product
                var price = card.find('.p-2:contains("Amount")').text().replace('Amount: ', '').trim();
                var tax = card.find('.p-2:contains("Tax")').text().replace('Tax: ', '').trim(); // Example if you have tax info
                var discount = card.find('.p-2:contains("Discount")').text().replace('Discount: ', '')
            .trim(); // Example if you have discount info
                $('#saleId').val(saleId);
                $('#sale_id').val(saleId);
                $('#quantity').val(quantity);
                $('#datetime').val(datetime);
                $('#product').val(product);
                $('#price').val(price);
                $('#tax').val(tax);
                $('#discount').val(discount);

                $('#paymentModal').modal('show');
                pauseTimer();

            }

            function submitPayment() {
                // Submit the form via jQuery

                $('#paymentForm').submit();
            }
        </script>
        <script>
            $('.single-select').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
            $('.multiple-select').select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        </script>
        <script>
            let timerInterval;
            let elapsedTime = 0;
            let paused = false;
            let remainingTime = 15; // Set the initial countdown time in seconds
            function formatState(opt) {
                if (!opt.id) {
                    return opt.text;
                }

                var optImage = $(opt.element).data('image');
                if (!optImage) {
                    return opt.text;
                } else {
                    var $opt = $(
                        '<span><img src="' + optImage + '" style="height: 30px; width: 30px; margin-right: 10px;" />' + opt
                        .text + '</span>'
                    );
                    return $opt;
                }
            };


            function startTimer() {
                if (remainingTime > 0) {
                    paused = false;
                    timerInterval = setInterval(() => {
                        // console.log(remainingTime);
                        remainingTime--;
                        if (remainingTime <= 0) {
                            clearInterval(timerInterval);
                            timerEnded();
                        }
                    }, 1000);
                }
            }

            function pauseTimer() {
                paused = true;
                // alert("timer paused");
                clearInterval(timerInterval);
            }

            function resetTimer() {
                clearInterval(timerInterval);
                elapsedTime = 0;
                paused = true;
                updateTime();
            }

            function timerEnded() {
                window.location.reload()
                // Add any other actions you want to perform when the timer ends here
            }
        </script>
        <script>
            var modal = $('#paymentModal');
            $(document).ready(function() {


                $('#sale-table .openable').click(populateModal);
                $('.modal [data-dismiss="modal"]').click(function() {
                    $('#paymentModal').modal('hide');
                })

                $('.payment-card').click(function() {
                    // Remove 'selected' class from all cards
                    $('.payment-card').removeClass('selected');

                    // Add 'selected' class to the clicked card
                    $(this).addClass('selected');

                    // Get the selected value
                    var selectedValue = $(this).data('value');
                    var customerRequired = $(this).data('customer-required');
                    if (customerRequired) {

                        customer = modal.find('#customers').val();
                        reg_no = modal.find('#reg_no').val();
                        if (!(customer && reg_no)) {
                            alert('Please select Customer and Vehicle');
                            $(this).removeClass('selected');
                            return;
                        }
                    }
                    // debugger;
                    // Set the value to the hidden select field
                    $('#Payment_method').val(selectedValue);
                    $(this).css('background-color', 'gray');

                    // Remove the click event listener from all payment cards
                    $('.payment-card').off('click');
                    // Submit the form
                    $('#paymentForm').submit();
                });
                $(".imageSelect").each(function() {
                    $(this).select2({
                        templateResult: formatState,
                        templateSelection: formatState,
                    });
                });
                @if ($autoUpdate)
                    startTimer();
                @endif
                $('#un_reg_no').on('change', function() {
                    pauseTimer();
                });
                $.ajax({
                    type: 'GET',
                    url: '{{ route('getCustomers') }}',
                    data: {},
                    success: function(data) {
                        var tr = $('#paymentModal');
                        tr.find('#un_reg_no').hide();
                        tr.find('#reg_no').show();
                        tr.find('#customers').show();
                        tr.find('.cust').empty();
                        tr.find('#customers').append('<option value="' +
                            1 + '">' + 'Please Select' +
                            '</option>');
                        $.each(data, function(index, customer) {
                            if (customer.CustomerBlocked == 1) {
                                tr.find('#customers').append('<option value="' +
                                    customer.id + '">' + customer.Des +
                                    ' (Blocked)</option>');

                            } else {
                                tr.find('#customers').append('<option value="' +
                                    customer.id + '">' + customer.Des +
                                    '</option>');

                            }
                        });
                    }
                });
                $('.payment').on('click', function() {
                    pauseTimer();
                })
                $('.payment').on('change', function() {
                    pauseTimer();
                    var paymentMethodId = $(this).val();
                    var tr = $(this).closest('tr');
                    if (paymentMethodId == 2) {

                    } else {
                        tr.find('[type="submit"][value="save"]').removeAttr('disabled');

                        tr.find('.cust').empty();
                        tr.find('#customers').hide();
                        tr.find('#walk_customers').show();
                        tr.find('#reg_no').hide();
                        tr.find('#un_reg_no').show();

                    }
                });

                $('.cust').on('change', function() {

                    var customerId = $(this).val();
                    var selectedOption = $(this).find('option:selected');


                    if (customerId != '' && customerId != '1') {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('getVehicles') }}',
                            data: {
                                customer_id: customerId
                            },
                            success: function(data) {
                                if (data.length) {

                                    const container = modal.find('#reg_no_container');
                                    container.empty();

                                    // Create and append select field
                                    const selectField =
                                        '<select class="form-select form-select-sm mb-3 veh" name="vid" id="reg_no" aria-label=".form-select-sm example">' +
                                        '<option value="">Please Select</option>';

                                    let options = '';
                                    $.each(data, function(index, vehicle) {
                                        if (vehicle.VehBlocked == 1) {
                                            options += '<option value="' + vehicle.id +
                                                '">' + vehicle.RegNO +
                                                ' (Blocked)</option>';
                                        } else {
                                            options += '<option value="' + vehicle.id +
                                                '">' + vehicle.RegNO + '</option>';
                                        }
                                    });

                                    container.append(selectField + options + '</select>');
                                } else {
                                    const container = modal.find('#reg_no_container');
                                    container.empty();

                                    // Create and append input field
                                    const inputField =
                                        '<input type="text" class="form-control mb-3 veh" name="reg_no" id="reg_no" placeholder="Enter Vehicle Registration">';
                                    container.append(inputField);
                                }
                            }
                        });
                    } else {
                        const container = modal.find('#reg_no_container');
                        container.empty();

                        // Create and append input field
                        const inputField =
                            '<input type="text" class="form-control mb-3 veh" name="reg_no" id="reg_no" placeholder="Enter Vehicle Registration">';
                        container.append(inputField);
                    }
                });
            });


            $('.veh').on('change', function() {
                var tr = $(this).closest('tr');
                var selectedOption = $(this).find('option:selected');
                var optionLabe2 = selectedOption.text();
                if (selectedOption.val() == '') {

                    tr.find('[type="submit"][value="save"]').attr('disabled', 'disabled');
                } else if (optionLabe2.includes('Blocked')) {
                    alert('Vehicle Blocked');
                    tr.find('[type="submit"][value="save"]').attr('disabled', 'disabled');

                } else {
                    tr.find('[type="submit"][value="save"]').removeAttr('disabled');
                }

            });
        </script>
        <!--app JS-->
        <script language="javascript"></script>
        <script src="assets/js/app.js"></script>
        <script>
            async function getBase64FromUrl(url) {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP ${response.status} - ${response.statusText}`);

                const blob = await response.blob();
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        const base64WithPrefix = reader.result; // "data:image/png;base64,...."
                        const pureBase64 = base64WithPrefix.split(",")[1]; // only pure base64
                        resolve(pureBase64);
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
            }

            // ---- Build Print Instructions ----
            async function generatePrintInstructions(saleData, settings) {
                // console.log(saleData);
                const instructions = [];
                const appUrl = "{{ url('/') }}";

                instructions.push({ type: "newline" });

                // ---- Company Logo ----
                let logoUrl = settings.logo || "";
                if (logoUrl && !logoUrl.startsWith("http")) {
                    logoUrl = appUrl + "/storage/" + logoUrl.replace("public/", "");
                }
                // console.log("settings.logo =", settings.logo);
                // console.log("Final logoUrl =", logoUrl);
                if (settings.logo) {
                    try {

                        instructions.push({
                            type: "printImage",
                            url:  logoUrl,
                            width: 200,
                            height: 100,
                            align: "center"
                        });
                        instructions.push({ type: "newline" });
                    } catch (e) {
                        console.error("❌ Failed to load logo:", e);
                    }
                }

                // ---- Header ----
                instructions.push({
                    type: "printCustom",
                    text: settings.name || "",
                    size: "boldMedium",
                    align: "center"
                });

                let lineInfo = "";
                if (settings.line1) lineInfo += settings.line1;
                if (settings.line2) lineInfo += (lineInfo ? " " : "") + settings.line2;

                if (lineInfo) {
                    instructions.push({
                        type: "printCustom",
                        text: lineInfo,
                        size: "medium",
                        align: "center"
                    });
                }

                instructions.push({ type: "newline" });

                // ---- Title ----
                const receiptTitle = saleData.bDuplicatePrint === true ? "Duplicate Sale Receipt" : "Sale Receipt";
                instructions.push({
                    type: "printCustom",
                    text: receiptTitle,
                    size: "boldLarge",
                    align: "center"
                });
                instructions.push({ type: "newline" });

                // ---- Sale Details ----
                instructions.push({
                    type: "printLeftRight",
                    left: `Nozzle No.: ${saleData.FC_NZNo || ""}`,
                    right: `Slip#: ${saleData.id || ""}`,
                    size: "medium"
                });
                function formatDate(dateString) {
                    const d = new Date(dateString);

                    let day = String(d.getDate()).padStart(2, "0");
                    let month = String(d.getMonth() + 1).padStart(2, "0");
                    let year = d.getFullYear();

                    let hours = d.getHours();
                    let minutes = String(d.getMinutes()).padStart(2, "0");

                    let ampm = hours >= 12 ? "PM" : "AM";
                    hours = hours % 12 || 12;
                    hours = String(hours).padStart(2, "0");

                    return `${day}-${month}-${year} ${hours}:${minutes} ${ampm}`;
                }

                const formatted = formatDate(saleData.pdate);

                instructions.push({
                    type: "printCustom",
                    text: `Date: ${formatted}`,
                    size: "medium",
                    align: "left"
                });
                instructions.push({
                    type: "printLeftRight",
                    left: "MOP: ",
                    right: saleData.paymentmethod || "",
                    size: "medium"
                });
                instructions.push({
                    type: "printLeftRight",
                    left: "Customer: ",
                    right: saleData.customerName || "Walk in Customer",
                    size: "medium"
                });
                instructions.push({ type: "newline" });

                // ---- Items Header ----
                instructions.push({
                    type: "print4Column",
                    col1: "Item",
                    col2: "Qty",
                    col3: "Rate",
                    col4: "Amount",
                    size: "bold"
                });

                // ---- Fuel Item ----
                instructions.push({
                    type: "print4Column",
                    col1: "Fuel",
                    col2: saleData.qty ? (saleData.qty / 100).toFixed(2) : "",
                    col3: saleData.rate ? (saleData.rate / 100).toFixed(2) : "",
                    col4: saleData.amt ? (saleData.amt / 100).toFixed(2) : "",
                    size: "medium"
                });
                instructions.push({ type: "newline" });

                // ---- Total ----
                instructions.push({
                    type: "printLeftRight",
                    left: "Total Amount:",
                    right: saleData.amt ? (saleData.amt / 100).toFixed(2) : "",
                    size: "boldLarge"
                });
                instructions.push({ type: "newline" });

                // ---- Footer ----
                instructions.push({
                    type: "printCustom",
                    text: "Powered by EzPump",
                    size: "bold",
                    align: "center"
                });
                instructions.push({ type: "newline" });
                instructions.push({
                    type: "printCustom",
                    text: "Thank You",
                    size: "bold",
                    align: "center"
                });
                instructions.push({ type: "newline" });

                // ---- QR Code ----
                if (settings.qrText) {
                    instructions.push({
                        type: "printQRcode",
                        text: settings.qrText,
                        width: 200,
                        height: 200,
                        align: "center"
                    });
                    instructions.push({ type: "newline" });
                }

                // ---- Paper Cut ----
                instructions.push({ type: "paperCut" });

                return instructions;
            }



            // --- Trigger Print (await use karna zaroori hai) ---
            async function triggerPrint(type, saleData, settingsData) {
                const instructions = await generatePrintInstructions(saleData, settingsData);

                const message = {
                    type: type,
                    data: instructions,
                    settings: settingsData
                };

                const messageString = JSON.stringify(message);
                console.log("📤 Sending to print channel:", messageString);

                if (window.flutter_inappwebview) {
                    window.flutter_inappwebview.callHandler('print-channel', messageString);
                } else if (window.printChannel) {
                    window.printChannel.postMessage(messageString);
                } else {
                    console.warn("⚠️ No print channel found.");
                }
            }

            {{--function generatePrintInstructions(saleData, settings) {--}}
            {{--    const instructions = [];--}}
            {{--    // Add a new line--}}
            {{--    --}}{{--const appUrl = "{{ url('/') }}";--}}
            {{--    instructions.push({ "type": "newline" });--}}
            {{--    const demoImage = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/200px-React-icon.svg.png";--}}
            {{--    console.log(demoImage);--}}
            {{--    instructions.push({--}}
            {{--        type: "printImage",--}}
            {{--        url: demoImage,--}}
            {{--        width: 200,--}}
            {{--        height: 200,--}}
            {{--        align: "center"--}}
            {{--    });--}}

            {{--    // Header info--}}
            {{--    instructions.push({--}}
            {{--        "type": "printCustom",--}}
            {{--        "text": settings.name || '',--}}
            {{--        "size": "boldMedium",--}}
            {{--        "align": "center"--}}
            {{--    });--}}
            {{--    // instructions.push({--}}
            {{--    //     "type": "printCustom",--}}
            {{--    //     "text": settings.address || '',--}}
            {{--    //     "size": "medium",--}}
            {{--    //     "align": "center"--}}
            {{--    // });--}}
            {{--    let lineInfo = '';--}}
            {{--    if (settings.line1) lineInfo += settings.line1;--}}
            {{--    if (settings.line2) lineInfo += (lineInfo ? ' ' : '') + settings.line2;--}}

            {{--    if (lineInfo) {--}}
            {{--        instructions.push({--}}
            {{--            "type": "printCustom",--}}
            {{--            "text": lineInfo,--}}
            {{--            "size": "medium",--}}
            {{--            "align": "center"--}}
            {{--        });--}}
            {{--    }--}}
            {{--    // instructions.push({--}}
            {{--    //     "type": "printCustom",--}}
            {{--    //     "text": `Phone: ${settings.phone || ''}`,--}}
            {{--    //     "size": "medium",--}}
            {{--    //     "align": "center"--}}
            {{--    // });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // Title--}}
            {{--    const receiptTitle = saleData.bDuplicatePrint === 1 ? "Duplicate Sale Receipt" : "Sale Receipt";--}}
            {{--    instructions.push({--}}
            {{--        "type": "printCustom",--}}
            {{--        "text": receiptTitle,--}}
            {{--        "size": "boldLarge",--}}
            {{--        "align": "center"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // Sale details--}}
            {{--    instructions.push({--}}
            {{--        "type": "printLeftRight",--}}
            {{--        "left": `Nozzle No.: ${saleData.FC_NZNo || ''}`,--}}
            {{--        "right": `Slip#: ${saleData.id || ''}`,--}}
            {{--        "size": "medium"--}}
            {{--    });--}}
            {{--    instructions.push({--}}
            {{--        "type": "printCustom",--}}
            {{--        "text": `Date: ${saleData.pdate || ''}`,--}}
            {{--        "size": "medium",--}}
            {{--        "align": "left"--}}
            {{--    });--}}
            {{--    instructions.push({--}}
            {{--        "type": "printLeftRight",--}}
            {{--        "left": "MOP: ",--}}
            {{--        "right": saleData.paymentmethod || '',--}}
            {{--        "size": "medium"--}}
            {{--    });--}}
            {{--    instructions.push({--}}
            {{--        "type": "printLeftRight",--}}
            {{--        "left": "Customer: ",--}}
            {{--        "right": saleData.customerName || "Walk in Customer",--}}
            {{--        "size": "medium"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // Items header--}}
            {{--    instructions.push({--}}
            {{--        "type": "print4Column",--}}
            {{--        "col1": "Item",--}}
            {{--        "col2": "Qty",--}}
            {{--        "col3": "Rate",--}}
            {{--        "col4": "Amount",--}}
            {{--        "size": "bold"--}}
            {{--    });--}}

            {{--    // Item row (fuel)--}}
            {{--    instructions.push({--}}
            {{--        "type": "print4Column",--}}
            {{--        "col1": "Fuel",--}}
            {{--        "col2": saleData.qty ? (saleData.qty / 100).toFixed(2) : '',--}}
            {{--        "col3": saleData.rate ? (saleData.rate / 100).toFixed(2) : '',--}}
            {{--        "col4": saleData.amt ? (saleData.amt / 100).toFixed(2) : '',--}}
            {{--        "size": "medium"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // Total--}}
            {{--    instructions.push({--}}
            {{--        "type": "printLeftRight",--}}
            {{--        "left": "Total Amount:",--}}
            {{--        "right": saleData.amt ? (saleData.amt / 100).toFixed(2) : '',--}}
            {{--        "size": "boldLarge"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // Footer--}}
            {{--    instructions.push({--}}
            {{--        "type": "printCustom",--}}
            {{--        "text": "Powered by EzPump",--}}
            {{--        "size": "bold",--}}
            {{--        "align": "center"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}
            {{--    instructions.push({--}}
            {{--        "type": "printCustom",--}}
            {{--        "text": "Thank You",--}}
            {{--        "size": "bold",--}}
            {{--        "align": "center"--}}
            {{--    });--}}
            {{--    instructions.push({ "type": "newline" });--}}

            {{--    // QR code--}}
            {{--    if (settings.qrText) {--}}
            {{--        instructions.push({--}}
            {{--            "type": "printQRcode",--}}
            {{--            "text": settings.qrText,--}}
            {{--            "width": 200,--}}
            {{--            "height": 200,--}}
            {{--            "align": "center"--}}
            {{--        });--}}
            {{--        instructions.push({ "type": "newline" });--}}
            {{--    }--}}

            {{--    // Cut paper--}}
            {{--    instructions.push({ "type": "paperCut" });--}}

            {{--    return instructions;--}}
            {{--}--}}

            {{--function triggerPrint(type, saleData, settingsData) {--}}
            {{--    const message = {--}}
            {{--        type: type,--}}
            {{--        data: generatePrintInstructions(saleData, settingsData),--}}
            {{--        settings: settingsData--}}
            {{--    };--}}

            {{--    const messageString = JSON.stringify(message);--}}
            {{--    console.log("Sending to print channel:", messageString);--}}

            {{--    // Flutter WebView handler--}}
            {{--    if (window.flutter_inappwebview) {--}}
            {{--        window.flutter_inappwebview.callHandler('print-channel', messageString);--}}
            {{--    }--}}
            {{--    // Browser/WebView (optional)--}}
            {{--    else if (window.printChannel) {--}}
            {{--        window.printChannel.postMessage(messageString);--}}
            {{--    }--}}
            {{--    else {--}}
            {{--        console.warn("⚠️ No print channel found. Message not delivered:", messageString);--}}
            {{--    }--}}
            {{--}--}}



            // function generatePrintInstructions(saleData, settings) {
            //     const instructions = [];
            //
            //     // Add a new line
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add company logo image (if URL is provided in settings)
            //     if (settings.logoUrl) {
            //         instructions.push({
            //             "type": "printImage",
            //             "url": settings.logoUrl
            //         });
            //         instructions.push({
            //             "type": "newline"
            //         });
            //     }
            //
            //     // Add company name, address, and phone number
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": settings.name || '',
            //         "size": "boldMedium",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": settings.address || '',
            //         "size": "medium",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": `Phone: ${settings.phone || ''}`,
            //         "size": "medium",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Determine receipt title
            //     const receiptTitle = saleData.bDuplicatePrint === 1 ? "Duplicate Sale Receipt" : "Sale Receipt";
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": receiptTitle,
            //         "size": "boldLarge",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add sale details
            //     instructions.push({
            //         "type": "printLeftRight",
            //         "left": `Nozzle No.: ${saleData.FC_NZNo || ''}`,
            //         "right": `Slip#: ${saleData.id || ''}`,
            //         "size": "medium"
            //     });
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": `Date: ${saleData.pdate || ''}`,
            //         "size": "medium",
            //         "align": "left"
            //     });
            //     instructions.push({
            //         "type": "printLeftRight",
            //         "left": "MOP: ",
            //         "right": saleData.paymentmethod || '',
            //         "size": "medium"
            //     });
            //     instructions.push({
            //         "type": "printLeftRight",
            //         "left": "Customer: ",
            //         "right": saleData.customerName || "Walk in Customer",
            //         "size": "medium"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add sale items header
            //     instructions.push({
            //         "type": "print4Column",
            //         "col1": "Item",
            //         "col2": "Qty",
            //         "col3": "Rate",
            //         "col4": "Amount",
            //         "size": "bold"
            //     });
            //
            //     // Add sale items (assuming single item "Fuel" for simplicity)
            //     instructions.push({
            //         "type": "print4Column",
            //         "col1": "Fuel",
            //         "col2": saleData.qty ? (saleData.qty / 100).toFixed(2) : '',
            //         "col3": saleData.rate ? (saleData.rate / 100).toFixed(2) : '',
            //         "col4": saleData.amt ? (saleData.amt / 100).toFixed(2) : '',
            //         "size": "medium"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add total amount
            //     instructions.push({
            //         "type": "printLeftRight",
            //         "left": "Total Amount:",
            //         "right": saleData.amt ? (saleData.amt / 100).toFixed(2) : '',
            //         "size": "boldLarge"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add footer messages
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": "Powered by EzPump",
            //         "size": "medium",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //     instructions.push({
            //         "type": "printCustom",
            //         "text": "Thank You",
            //         "size": "bold",
            //         "align": "center"
            //     });
            //     instructions.push({
            //         "type": "newline"
            //     });
            //
            //     // Add QR code (if needed)
            //     if (settings.qrText) {
            //         instructions.push({
            //             "type": "printQRcode",
            //             "text": settings.qrText,
            //             "width": 200,
            //             "height": 200,
            //             "align": "center"
            //         });
            //         instructions.push({
            //             "type": "newline"
            //         });
            //     }
            //
            //     // Add paper cut command
            //     instructions.push({
            //         "type": "paperCut"
            //     });
            //
            //     return instructions;
            // }
            //
            // function triggerPrint(type, saleData, settingsData) {
            //     // alert("Got data to print");
            //     // Prepare the message object
            //     const message = {
            //         type: type, // This can be 'saledata' or any other type based on the logic
            //         data: generatePrintInstructions(saleData, settingsData),
            //         settings: settingsData
            //     };
            //
            //     // Convert the message object to a JSON string
            //     const messageString = JSON.stringify(message);
            //     console.log(messageString);
            //     // Trigger the Flutter print-channel
            //     if (window.flutter_inappwebview) {
            //         window.flutter_inappwebview.callHandler('print-channel', messageString);
            //     } else if (window.printChannel) {
            //         window.printChannel.postMessage(messageString);
            //     } else if (printChannel) {
            //         printChannel.postMessage(messageString);
            //     }
            // }

            // Example sale data and settings to trigger print
            @php
                $print_data = session('print_data');
            @endphp
            @if (isset($print_data))
            @php
                $settings = \App\Models\Settings::getSettingsArray();
                $descriptions = PaymentMethod::where('erp_id', $print_data->p_mode ?? 1)->first();
//dd($print_data->customer_id);

                $cust = customer::where('id', $print_data->customer_id)->value('Des');

                $print_data->customerName = $cust;
//                dd($descriptions,$print_data->p_mode);
                $print_data->paymentmethod = $descriptions->Des;
            @endphp
            const saleData = @json($print_data);
            console.log(saleData);

            const settingsData = @json($settings);

            // Trigger the print-channel with the example data
            triggerPrint('print', saleData, settingsData);
            @endif
        </script>
</body>

</html>
