@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="app">
<style>
    pre{
        height: 20em;
        overflow: auto;
        display: none!important;
    }
</style>
<div class="row">
{{--    @php--}}
{{--    $calendarIds=\Illuminate\Support\Facades\DB::table('shift_calendars')->take(2)->orderBy('id','desc')->get();--}}

{{--    @endphp--}}
{{--    <div class="text-end">--}}
{{--        <select class="form-control w-25" v-model="calendar_id">--}}
{{--            <option value="">Select Calendar Id</option>--}}
{{--            @foreach($calendarIds as $calendarId)--}}
{{--                <option value="{{ $calendarId->id }}">{{ $calendarId->id }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--        <br><br>--}}
{{--    </div>--}}
    <summary-report-form />

</div>

</div>
@endsection
