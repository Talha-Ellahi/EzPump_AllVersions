@extends('layouts.app')

@section('content')



<div class="wrapper">

<!--start page wrapper -->

  <div class="container">

     <div class="card border-top border-0 border-4 border-danger">
        
          <div class="card-body ">
          <div class="card-title text-center">
		  <img class="logo" src="assets/images/ezpump_dashboard.png" alt="Logo">
            </div>
            <h6 class="mb-0 text-uppercase"><b>Prices Change Logs</b></h6>
            <hr>
				 

				 <div class="table-responsive new_style_table table-striped">
							<table id="example" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>Sr #</th>
										<th>Product Name</th>
										<th>Old Price</th>
										<th>New Price</th>
										<th>Date & Time</th>
									</tr>
								</thead>
								<tbody>
								@php $serial = 1; @endphp
  								@foreach ($Logs as $log)
  								  <tr>
  								    <td>{{ $serial }}</td>
  								    <td>{{ $log->ITMNAME }}</td>
  								    <td>{{ $log->Previous_Rate/100 }}</td>
  								    <td>{{ $log->New_Rate }}</td>
  								    <td>{{ $log->updated_at->format('j F Y g:i:s A') }}</td>
  								  </tr>
  								  @php $serial++; @endphp
  								@endforeach
								
							</table>
						</div>
	         
	
    <!--end row-->

  </div>
</div>

</div>
<!--end wrapper-->


<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

@endsection
