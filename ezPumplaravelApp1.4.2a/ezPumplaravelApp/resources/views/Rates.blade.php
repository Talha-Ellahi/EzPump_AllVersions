@extends('layouts.app')
<style>
  .d-grid {
    display: grid !important;
    justify-content: center;
  }
</style>
@section('content')
@php
  use \App\Models\Settings;
  $settings = Settings::getSettingsArray();
  // Define an array of background colors
  $backgroundColors = ['bg-primary', 'bg-danger', 'bg-success', 'bg-dark'];
  // Initialize a counter variable
  $colorIndex = 0;
@endphp

<div class="wrapper">

  <!--start page wrapper -->

  <div class="page-content">

    <div class="container">
      <div class="card border-top border-0 border-4 border-danger">

          <div class="card-body ">
            <div class="card-title text-center">
              <img width="100" src="/assets/images/ezpump logo landscape.png" alt="Logo">
            </div>
            <h6 class="mb-0 text-uppercase"><b>current Prices</b></h6>
            <hr>
            <div class="row ">
              @foreach ($Products as $Product)
                @php
            // Get the current background color from the array
            $bgColor = $backgroundColors[$colorIndex % count($backgroundColors)];
            // Increment the counter
            $colorIndex++;
          @endphp
                <div class="col-lg-4">
                <div class="card radius-10 {{$bgColor}} bg-gradient">
                  <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div>
                    <p class="mb-0 text-white"><strong>{{$Product->ITMNAME}}</strong></p>
                    <h5 class="my-1 text-white"><b>Rs. {{$Product->SRATE / 100}}</b></h5>
                    </div>
                  
                  </div>
                  </div>
                </div>
                </div>
        @endforeach
            </div>
            <br>

            <h6 class="mb-0 text-uppercase"><b>Update New Price</b></h6>
            <hr>
            <div class="row row-cols-1 row-cols-md-4 row-cols-xl-3 justify-content-center">
              @foreach ($Products as $Product)    
          <form action="/Rates" method="post">
          @csrf
          <div class="col">
            <div class="card radius-10">
            <div class="card-body">
              <div class="text-center">
              <label class="form-label"><b>{{$Product->ITMNAME}}</b></label>
              <div class="input-group">
                <input type="text" class="form-control border-start-0" name="Latest_Price"
                placeholder="Enter New Price">
              </div>
              <div class="input-group" style="display:none">
                <input type="text" class="form-control border-start-0" name="Code"
                value="{{$Product->ICODE}}">
              </div>

              </div>

            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-dark" style="margin: 0px 50px 10px 50px;"><i
                class="fadeIn animated bx bx-refresh"></i>Update Prices</button>
            </div>
            </div>
          </div>
          </form>
        @endforeach


        <div class="d-grid">
          <a href="/Logs">
            <button type="submit" class="btn btn-primary px-5">View Logs</button>
          </a>
        </div>

            </div> 
          </div>
          <!--end row-->

        </div>
      <!--end page wrapper -->

    </div>
    <!--end wrapper-->

    @endsection