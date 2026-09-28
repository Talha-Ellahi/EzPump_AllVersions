<!DOCTYPE html>
<html>

<head>
    <title> EzPump | Sales Dashboard
    </title>
    <meta charset="utf-8">
    @vite([
    'resources/js/app.js',
    'resources/sass/app.scss',
    ])


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

    <link href="assets/css/new_style.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <style>
        .filter-label {
            white-space: nowrap;
        }

        .payment-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s ease;
        }

        .payment-card.selected {
            border-color: #007bff;
            /* Change to the color you prefer */
            background-color: #e9f5ff;
        }

        .cstm_btn {
            border: 1px solid transparent;
            padding: 12px;
            font-size: 0.9rem;
            margin-right: 0.5rem !important;
            border-radius: 10px;
        }
    </style>
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
</head>

<body class="bg-login">
    <wrapper-->
        <div class="wrapper">

            <!--start page wrapper -->
            @include('layouts.header')


            <div class="page-content" style="height: 100vh; margin-top: 3em">
                <!-- Filter Form -->

                <form id="filterForm" onsubmit="applyFilters(event)" class="form-inline mb-3">

                    <nav class="navbar navbar-expand-lg rounded navbar-light">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent5" aria-controls="navbarSupportedContent5" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent5">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li style="min-width: 150px;">
                                        <p class="nav-link">
                                            <label for="nozzles" class="filter-label">Nozzles:</label>
                                            <select id="nozzles" size="1" name="nozzles[]" class="form-select ml-2" multiple>
                                                @foreach ($nozzles as $nozzle)

                                                <option value="{{$nozzle->FC_NZNo}}">{{$nozzle->FC_NZNo??null}}</option>


                                                @endforeach
                                                <!-- Add other nozzles as needed -->
                                            </select>
                                        </p>
                                    </li>


                                    <li>
                                        <p class="nav-link">
                                            <label for="startDate" class="filter-label">Start Date:</label>
                                            <input type="datetime-local" id="startDate" name="startDate" class="form-control ml-2">
                                    </li>

                                    <li>
                                        <p class="nav-link">
                                            <label for="endDate" class="filter-label">End Date:</label>
                                            <input type="datetime-local" id="endDate" name="endDate" class="form-control ml-2"
                                                value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                                    </li>
                                    <li>
                                        <p class="nav-link">
                                            <label for="qtyMin" class="filter-label">Qty Min:</label>
                                            <input type="text" id="qtyMin" name="qtyMin" class="form-control ml-2"
                                                style="width: 100px;" value="0">
                                    </li>
                                    <li>
                                        <p class="nav-link">
                                            <label for="qtyMax" class="filter-label">Qty Max:</label>
                                            <input type="text" id="qtyMax" name="qtyMax" class="form-control ml-2"
                                                style="width: 100px;" value="100000">
                                    </li>
                                    <li class="nav-item">
                                        <p class="nav-link"> <input type="hidden" id="selectedPaymentMethods" name="selectedPaymentMethods" value=""></p>
                                    </li>

                                </ul>


                                <p class="d-flex">
                                    <button class="cstm_btn btn-primary me-3" data-toggle="modal" data-target="#paymentModal"><i class="bx bx-dollar"></i>Payment Method</button>
                                    <button class="cstm_btn btn-info me-3" data-toggle="modal" data-target="#productSaleModal"><i class="bx bx-cart"></i>Product Sale</button>
                                    <button class="cstm_btn btn-success me-3" type="submit"><i class="bx bx-navigation"></i> Apply Filter</button>
                                    <button class="cstm_btn btn-danger  me-3" onclick="clearFilters()"><i class="bx bx-x"></i>Clear</button>
                                </p>
                            </div>
                        </div>
                    </nav>

                </form>
                <!-- Payment Method Modal -->
                <div class="modal   fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @foreach ($payments as $payment)
                                    <div class="col-md-3 col-lg-2 col-sm-4">
                                        <div class="card payment-card" data-id="{{ $payment->id }}">
{{--                                            <img class="card-img-top" src="/assets/images/{{ $payment->logo_profile }}" alt="{{ $payment->Des }}">--}}
                                            <img
                                                src="{{$payment->file}}" alt="Test Image">

                                            <div class="card-body text-center">
                                                <p class="card-text">{{ $payment->Des }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="confirmPaymentSelection">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Sale Modal -->
                <div class="modal fade" id="productSaleModal" tabindex="-1" aria-labelledby="productSaleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productSaleModalLabel">Product Sale</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="productSaleForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="product_select">Product</label>
                                                <select id="product_select" name="product_id" class="form-control" required>
                                                    <option value="">Select Product</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="customer_select">Customer</label>
                                                <select id="customer_select" name="customer_id" class="form-control" required>
                                                    <option value="">Select Customer</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" value="1" id="quantity" name="quantity" class="form-control" step="1" min="1" required style="-webkit-appearance: textfield; -moz-appearance: textfield;">
                                                <small class="text-muted">Unit: <span id="product_unit">-</span></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="rate">Rate</label>
                                                <input type="number" id="rate" name="rate" class="form-control" step="0.01" min="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="amount">Amount</label>
                                                <input type="number" id="amount" name="amount" class="form-control" step="0.01" min="0.01" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="payment_method_select">Payment Method</label>
                                                <select id="payment_method_select" name="payment_method" class="form-control" required>
                                                    <option value="">Select Payment Method</option>
                                                    @foreach ($payments as $payment)
                                                    <option value="{{ $payment->id }}" @if($payment->id == 1) selected @endif>{{ $payment->Des }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customer_credit_info" class="alert alert-info" style="display: none;">
                                        <strong>Customer Credit Info:</strong>
                                        <br>Credit Limit: <span id="credit_limit">0</span>
                                        <br>Used: <span id="credit_used">0</span>
                                        <br>Available: <span id="credit_available">0</span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="submitProductSale">Save Sale</button>
                            </div>
                        </div>
                    </div>
                </div>

                <iframe id="dataframe" src="/dataframe" width="100%" height="100%"></iframe>
                @php
                    use Illuminate\Support\Facades\DB;

                    /* ========================================= */
                    /* ========== GET GIT TAG ================== */
                    /* ========================================= */

                    $tag = '1.0.0';

                    try {
                        $tagOutput = [];
                        exec('git tag -l "[0-9]*" --sort=-version:refname 2>&1', $tagOutput);

                        if (!empty($tagOutput[0]) && !str_contains(strtolower($tagOutput[0]), 'fatal')) {
                            $tag = trim($tagOutput[0]);
                        }

                    } catch (\Exception $e) {
                        $tag = '1.0.0';
                    }

                    /* ========================================= */
                    /* ========== FINAL VERSION STRING ========= */
                    /* ========================================= */

                    $version = $tag;

                @endphp

                <footer class="text-center text-muted py-3 bg-light border-top">
                    <small>
                        &copy; {{ now()->year }} EZPump. All rights reserved.
                        | Version: {{ $version }}
                    </small>
                </footer>
            </div>
        </div>

        <script>
            const iframe = document.getElementById('dataframe');

            document.querySelectorAll('.payment-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Toggle the 'selected' class on click
                    this.classList.toggle('selected');
                });
            });

            document.getElementById('confirmPaymentSelection').addEventListener('click', function() {
                // Collect selected payment method IDs
                const selectedPayments = Array.from(document.querySelectorAll('.payment-card.selected'))
                    .map(card => card.getAttribute('data-id'));

                // Update hidden input field with selected payment methods
                document.getElementById('selectedPaymentMethods').value = selectedPayments.join(',');

                // Close the modal
                $('#paymentModal').modal('hide');
                applyFilters();
            });


            function applyFilters(event) {

                if (event) {
                    event.preventDefault();

                }
                runIframeFunction('pauseTimer');
                // Get filter values
                const nozzles = Array.from(document.getElementById('nozzles').selectedOptions).map(option => option.value);
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                const amountMin = ''; // document.getElementById('amountMin').value;
                const amountMax = ''; // document.getElementById('amountMax').value;
                const qtyMin = document.getElementById('qtyMin').value;
                const qtyMax = document.getElementById('qtyMax').value;
                const selectedPaymentMethods = document.getElementById('selectedPaymentMethods').value;

                // Create query string
                const queryString = new URLSearchParams({
                    nozzles: nozzles.join(','),
                    startDate,
                    endDate,
                    amountMin,
                    amountMax,
                    qtyMin,
                    qtyMax,
                    paymentMethods: selectedPaymentMethods // Add payment methods to query string

                }).toString();

                // Reload iframe with query string
                document.getElementById('dataframe').src = '/dataframe?' + queryString;

            }

            function runIframeFunction(functionName, ...args) {
                if (iframe.contentWindow && typeof iframe.contentWindow[functionName] === 'function') {
                    iframe.contentWindow[functionName](...args);
                } else {
                    console.error(`Function ${functionName} not found in iframe.`);
                }
            }

            function clearFilters() {
                document.location.reload();
            }
        </script>
        <script src="assets/js/jquery.min.js"></script>

        <!-- <script src="assets/js/bootstrap.bundle.min.js"></script> -->
        <script>
            $('[data-target="#paymentModal"]').click(function() {
                $('#paymentModal').modal('show');
            });
            $('[data-dismiss="modal"]').click(function() {
                $('#paymentModal').modal('hide');
            });
        </script>
        <script>
            // Capture user's local time and timezone offset
            const userLocalTime = new Date(); // Get the current local time
            const userTimeZoneOffset = userLocalTime.getTimezoneOffset(); // Get the offset from UTC in minutes

            // Prepare the data to be sent
            const data = {
                user_time: userLocalTime.toISOString(), // Convert local time to ISO format
                user_offset: userTimeZoneOffset // Send the timezone offset in minutes
            };

            // Use fetch API to send a POST request
            fetch('/api/updateTime', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data) // Send the data as JSON
                })
                .then(response => response.json()) // Handle the response
                .then(data => console.log('Success:', data))
                .catch((error) => console.error('Error:', error));
        </script>
        <script src="assets/plugins/select2/js/select2.min.js">
        </script>
        <script>
            $(document).ready(function() {
                $('#nozzles').select2({
                    width: '100%'
                });

                // Initialize Select2 for product and customer dropdowns
                $('#product_select').select2({
                    placeholder: 'Search for a product...',
                    allowClear: true,
                    ajax: {
                        url: '/api/products',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                $('#customer_select').select2({
                    placeholder: 'Search for a customer...',
                    allowClear: true,
                    ajax: {
                        url: '/api/customers',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                // Product selection change event
                $('#product_select').on('select2:select', function (e) {
                    var data = e.params.data;
                    if (data.SRATE) {
                        $('#rate').val((data.SRATE / 100).toFixed(2));
                        calculateAmount();
                    }
                    if (data.UOM) {
                        $('#product_unit').text(data.UOM);
                    }
                });

                // Customer selection change event
                $('#customer_select').on('select2:select', function (e) {
                    var data = e.params.data;
                    if (data.CreditLimit !== undefined) {
                        $('#credit_limit').text(data.CreditLimit);
                        $('#credit_used').text(data.LimitUsed);
                        $('#credit_available').text((data.CreditLimit - data.LimitUsed).toFixed(2));
                        $('#customer_credit_info').show();
                    }
                });

                // Calculate amount when quantity or rate changes
                $('#quantity, #rate').on('input', function() {
                    calculateAmount();
                });

                function calculateAmount() {
                    var quantity = parseFloat($('#quantity').val()) || 0;
                    var rate = parseFloat($('#rate').val()) || 0;
                    var amount = quantity * rate;
                    $('#amount').val(amount.toFixed(2));
                }

                // Product Sale Modal handlers
                $('[data-target="#productSaleModal"]').click(function() {
                    $('#productSaleModal').modal('show');
                });

                $('#submitProductSale').click(function() {
                    var formData = {
                        _token: $('input[name="_token"]').val(),
                        product_id: $('#product_select').val(),
                        customer_id: $('#customer_select').val(),
                        quantity: $('#quantity').val(),
                        rate: $('#rate').val(),
                        amount: $('#amount').val(),
                        payment_method: $('#payment_method_select').val()
                    };

                    // Validate form
                    if (!formData.product_id  || !formData.quantity || !formData.rate || !formData.payment_method) {
                        alert('Please fill all required fields');
                        return;
                    }
                    // if payment_method is 2(credit) then check if customer has enough credit
                    if (formData.payment_method == 2) {
                        // verify if customer is selected
                        if (!formData.customer_id) {
                            alert('Please select a customer for credit payment.');
                            return;
                        }
                        var creditLimit = parseFloat($('#credit_limit').text());
                        var creditUsed = parseFloat($('#credit_used').text());
                        var creditAvailable = creditLimit - creditUsed;
                        if (parseFloat(formData.amount) > creditAvailable) {
                            alert('Customer does not have enough credit to complete this sale.');
                            return;
                        }
                    }

                    // Submit the form
                    $.ajax({
                        url: '/api/product-sale',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                alert('Product sale recorded successfully!');
                                $('#productSaleModal').modal('hide');
                                $('#productSaleForm')[0].reset();
                                $('#product_select').val(null).trigger('change');
                                $('#customer_select').val(null).trigger('change');
                                $('#customer_credit_info').hide();
                                $('#product_unit').text('-');

                                // Refresh the iframe to show new sale
                                document.getElementById('dataframe').src = document.getElementById('dataframe').src;
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            var errorMessage = 'An error occurred';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            alert('Error: ' + errorMessage);
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(() => {
                    const userRole = @json(auth()->user()->role);
                    const cacheLocked = @json(Cache::get('system_locked'));
                    const bypassUntil = @json(Cache::get('bypass_active_until'));
                    const minutesRemaining = @json(Cache::get('shift_minutes_remaining'));

                    console.log(cacheLocked);
                    let lockScreenVisible = false;
                    let lockBeepInterval = null;

                    // Skip lock for Admin/SuperAdmin
                    if (cacheLocked === true && userRole != 0 && userRole != 2) {
                        showLockScreen();
                    }
                    else {
                        console.log(minutesRemaining);
                        if (minutesRemaining <= 55 && minutesRemaining > 0 && userRole != 0 && userRole != 10) {
                            const now = new Date();
                            const bypassActiveUntil = bypassUntil ? new Date(bypassUntil) : null;

                            if (!bypassActiveUntil || now > bypassActiveUntil) {
                                showReminder(minutesRemaining);
                            }
                        }
                    }

                    // Show lock screen function
                    function showLockScreen() {
                        if (lockScreenVisible) return;
                        lockScreenVisible = true;

                        const lockHTML = `
                    <div class="a1b2c3">
                        <div class="d4e5f6"></div>
                        <div class="g7h8i9">
                            <div class="j0k1l2">
                                <svg class="m3n4o5" viewBox="0 0 24 24">
                                    <path d="M12 2C8.14 2 5 5.14 5 9v1.5c-1.85.97-3 2.9-3 5.1V21h18v-5.4c0-2.2-1.15-4.13-3-5.1V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v1.5H7V9c0-2.76 2.24-5 5-5z"/>
                                </svg>
                            </div>
                            <h1 class="p6q7r8">Access Locked</h1>
                            <p class="s9t0u1">Your session has ended due to shift expiry.</p>
                            <div class="v2w3x4">Locked</div>
                            <div class="y5z6a7">Please contact administrator to unlock access.</div>
                        </div>
                    </div>
                `;

                        document.body.insertAdjacentHTML('beforeend', lockHTML);
                        document.body.classList.add('lock-mode');

                        // Start Continuous Beep Loop
                        if (!lockBeepInterval) {
                            lockBeepInterval = setInterval(() => {
                                const softBeep = new Audio('/beep/beep.wav');
                                softBeep.play().catch(e => console.error(e));
                            }, 2000);
                        }
                    }

                    // Show shift reminder function
                    function showReminder(minsLeft) {
                        if (document.querySelector('.shift-reminder')) return;

                        const reminderHTML = `
                    <div class="luxury-reminder-screen shift-reminder">
                        <div class="reminder-content">
                            <h2>⚠️ Shift Closing Soon</h2>
                            <p>Shift will lock in <strong>${minsLeft} minutes</strong>.</p>
                        </div>
                    </div>
                `;
                        document.body.insertAdjacentHTML('beforeend', reminderHTML);

                        // Auto-remove after 1 minute
                        setTimeout(() => {
                            const reminderEl = document.querySelector('.shift-reminder');
                            if (reminderEl) reminderEl.remove();
                        }, 60000);

                        // Beep Sound
                        const beep = new Audio('/beep/beep.wav');
                        beep.play().catch(e => console.error(e));
                    }
                }, 300000); // 5 minte delay
            });
        </script>

        <style>
            .lock-mode { overflow: hidden !important; touch-action: none !important; }
            .a1b2c3 {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                display: flex; justify-content: center; align-items: center;
                background: linear-gradient(135deg, #1a1a1a, #000000);
                z-index: 9999; overflow: hidden; animation: fadeInScreen 0.5s ease-out;
            }
            .d4e5f6 {
                position: absolute; width: 200%; height: 200%;
                background: radial-gradient(circle at center, rgba(255, 0, 0, 0.1), transparent 70%);
                animation: pulseGlow 3s infinite ease-in-out; z-index: 1;
            }
            .g7h8i9 {
                z-index: 2; background: rgba(30, 30, 30, 0.9);
                padding: 2.5rem 3rem; border-radius: 20px;
                box-shadow: 0 0 30px rgba(255, 0, 0, 0.4);
                text-align: center; color: #fff; max-width: 500px;
                animation: zoomIn 0.6s ease-out;
            }
            .j0k1l2 {
                background: rgba(220, 53, 69, 0.2); border-radius: 50%;
                padding: 1.2rem; margin-bottom: 1.5rem;
                animation: pulseBorder 2s infinite; display: inline-block;
            }
            .m3n4o5 {
                width: 60px; height: 60px; fill: #ff4d4d;
                filter: drop-shadow(0 0 10px rgba(255, 77, 77, 0.6));
            }
            .p6q7r8 {
                font-size: 2.2rem; margin-bottom: 0.8rem; font-weight: 700;
                background: linear-gradient(to right, #fff, #ff9999);
                -webkit-background-clip: text; color: transparent;
            }
            .s9t0u1 {
                font-size: 1.1rem; color: rgba(255, 255, 255, 0.8);
                margin-bottom: 1.5rem;
            }
            .v2w3x4 {
                font-size: 1rem; background: rgba(255, 77, 77, 0.15);
                border: 1px solid rgba(255, 77, 77, 0.4);
                border-radius: 20px; padding: 0.5rem 1rem;
                display: inline-block; margin-bottom: 1rem; color: #fff;
            }
            .y5z6a7 {
                font-size: 0.95rem; color: rgba(255, 255, 255, 0.7);
            }
            .luxury-reminder-screen {
                position: fixed; bottom: 20px; right: 20px;
                background: rgba(255, 193, 7, 0.95); color: #000;
                padding: 1rem 1.5rem; border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 9999;
                font-family: 'Inter', sans-serif; animation: slideUp 0.5s ease;
            }
            .reminder-content h2 { font-size: 1.2rem; margin-bottom: 0.5rem; }
            .reminder-content p { font-size: 0.95rem; margin: 0; }
            @keyframes pulseGlow {
                0%, 100% { transform: scale(1); opacity: 0.8; }
                50% { transform: scale(1.1); opacity: 1; }
            }
            @keyframes pulseBorder {
                0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
                50% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
            }
            @keyframes fadeInScreen {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes zoomIn {
                from { transform: scale(0.9); opacity: 0; }
                to { transform: scale(1); opacity: 1; }
            }
        </style>


</body>

</html>
